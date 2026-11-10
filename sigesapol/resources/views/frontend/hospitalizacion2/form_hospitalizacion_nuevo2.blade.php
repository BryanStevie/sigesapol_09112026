@extends('frontend.layouts.master3')
@section('renderjs')

<script>
  $obtener_id_de_emergencia = '{{$id_emergencia}}'; 
  
  ///LISTAMOS LOS TIPOS DE SEGUROS numero_de_referencia
  // lista_parametros('tipo_seguros','id_tipo_seguro','combo','TIPO_SEGUROS',1);
  ///CAMPOS SOLO NUMEROS
  // validar_campos_solo_numerico('#telefono');
  // validar_campos_solo_numerico('#telefono2');
  // validar_campos_solo_numerico('#dni_beneficiario');
  // validar_campos_solo_numerico('#nro_historia');
  // validar_campos_solo_numerico('#hora_alta_medica');
  // validar_campos_solo_numerico('#fecha_alta_medica');
  // validar_campos_solo_numerico('#nro_doc_ident_responsable');
  // validar_campos_solo_numerico('#telefono_responsable');
  // validar_campos_solo_numerico('#nro_doc_ident_acompaniante');
  // validar_campos_solo_numerico('#telefono_acompaniante');
  // validar_campos_solo_numerico('#numero_de_referencia');
  // validar_campos_solo_numerico('#telefono_apoderado');  
  // validar_campos_solo_numerico('#nro_doc_ident_apoderado');  

  function cambiar_de_PestanaTab(descripcion_de_tab) {  
    var tab_seleccionado = document.querySelector('a[href="#' + descripcion_de_tab + '"]'); 
    tab_seleccionado.click();
  }

  $('#cancelar_registro_admision').click(function () {  

    swal({
      buttons: true,
      title: "¿Desea Cancelar el Registro?",
      text: "Recuerde que si CANCELAR EL REGISTRO, perderá toda la información ingresada.",
      icon: "warning",
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {     
        window.location.href = '{{url('all_pendientes_hospitalizacion/')}}'; 
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

  $("#tipodoc_beneficiario_bus").select2({ width: 'resolve'});
  
  if($obtener_id_de_emergencia==''){
    $("#origen").select2({ width: 'resolve'});
    $("#id_upss_encargado").select2({ width: 'resolve'});
    $("#id_servicio_encargado").select2({ width: 'resolve'});
    $("#campo_busqueda_medico").select2({ width: 'resolve'});
  }

  $("#id_servicio_destino").select2({ width: 'resolve'});
  $("#id_consultorio").select2({ width: 'resolve'});
  $("#id_sala").select2({ width: 'resolve'});
  
  if($obtener_id_de_emergencia ==''){
    $("#id_servicio").select2({ width: 'resolve'});
  }

  $("#id_cama").select2({ width: 'resolve'});
  $("#id_etnia").select2({ width: 'resolve'});
  $("#id_tipo_sangre").select2({ width: 'resolve'});
  $("#id_estado_civil").select2({ width: 'resolve'});
  $("#id_religion").select2({ width: 'resolve'});
  $("#id_grado_instruccion").select2({ width: 'resolve'});
  $("#id_ocupacion").select2({ width: 'resolve'});
  $("#id_departamento_procedencia").select2({ width: 'resolve'});
  $("#id_provincia_procedencia").select2({ width: 'resolve'});
  $("#id_distrito_procedencia").select2({ width: 'resolve'});
  $("#id_departamento_domicilio").select2({ width: 'resolve'});
  $("#id_provincia_domicilio").select2({ width: 'resolve'});
  $("#id_distrito_domicilio").select2({ width: 'resolve'});
  $("#id_tipo_apoderado").select2({ width: 'resolve'});
  $("#id_tipo_acompaniante").select2({ width: 'resolve'});
  $("#tipo_doc_apoderado_busqueda").select2({ width: 'resolve'});
  
  // remplazar por un evento on change en remplaz de   validar_al_escribir_fechas('decha_atencion');
  $('#fecha_atencion').on('change', function() {
    var fechaIngresada = $(this).val();
    var fechaActual = new Date();
    var ingresarFechaPartes = fechaIngresada.split('/');
    var ingresarDia = parseInt(ingresarFechaPartes[0], 10);
    var ingresarMes = parseInt(ingresarFechaPartes[1], 10) - 1; 
    var ingresarAño = parseInt(ingresarFechaPartes[2], 10);
    
    // Crear un objeto de fecha con la fecha ingresada manualmente
    var fechaSeleccionar = new Date(ingresarAño, ingresarMes, ingresarDia);

    // Si la fecha ingresada es posterior a la fecha actual, restablecerla a la fecha actual
    // si es 28/03 - seria27/03 
    if (fechaSeleccionar > fechaActual) {
      $(this).datepicker('setDate', fechaActual);
    }
  });

  // implementar fechas para bloquear data futuras hacia adelante
  $('#fecha_atencion').datepicker({
    language: 'es',
    useCurrent: false,
    format: 'dd/mm/yyyy', 
    todayHighlight: true,
    autoclose: true,  
    orientation: "bottom right",
    //utilizando beforeshoday para las fechas posteriores
    beforeShowDay: function(data) {
      var fechaActual = new Date();
      if (data > fechaActual) {
        return {
          enabled: false
        };
      }
      return {
        enabled: true
      };
    }
  })
  
  //=============================================================================
  ////evento al boton de buscar paciente 

  $('#btnBuscarDniPaciente').click(function() {  
    getReniecByDni_paciente('2', null);
  });

  $('#btn_buscar_apoderado').click(function() {  
    buscar_apoderado();
  });

  $('#btn_buscar_acompaniante').click(function() {  
    buscar_acompaniante();    
  });

  

  function limpiar_paciente(){  
    $("#tab1DatosPacienteTitularLI").hide();  

    $('#nro_doc_ident').val(""); 
    $('#nombre_beneficiario').val(""); 
    $('#beneficiario').val(""); 
    $('#paterno_beneficiario').val(""); 
    $('#materno_beneficiario').val(""); 
    $('#fecha_nac').val(""); 
    $('#fecha_nacimiento_beneficiario').val(""); 
    $('#sexo').val(""); 
    $('#edad_detallada').val(""); 
    $('#edad').val(""); 
    $('#nro_historia').val(""); 
    $('#tipo_beneficiario').val("");  
    $('#parentesco_paciente').val(""); 
    $('#situacion_paciente').val(""); 
    $('#grado_paciente').val(""); 
    
    $('#foto_paciente').val("");  
    var imagen = document.getElementById("foto_imagen_paciente");   
    imagen.src = "";

    // Ubicar tipo apoderado en SOLO
    $('#id_tipo_apoderado').val('8').change();
    // Ubicar tipo doc apoderado en DNI
    $('#tipo_doc_apoderado_busqueda').val('1').change();
    // Limpiar todos los campos
    $('#nro_doc_apoderado_busqueda').val('');
    $('#nro_doc_apoderado').val('');
    $('#tipo_doc_apoderado').val('');
    $('#nombre_apoderado').val('');
    $('#telefono_apoderado').val('');
    // Ocultar panel apoderado
    // $('#panel_apoderado').hide();

    // Ubicar tipo apoderado en SOLO
    $('#id_tipo_acompaniante').val('8').change();
    // Ubicar tipo doc apoderado en DNI
    $('#tipo_doc_acompaniante_busqueda').val('1').change();
    // Limpiar todos los campos
    $('#nro_doc_acompaniante_busqueda').val('');
    $('#nro_doc_acompaniante').val('');
    $('#tipo_doc_acompaniante').val('');
    $('#nombre_acompaniante').val('');
    $('#telefono_acompaniante').val('');

    $('#dni_beneficiario').focus();
  }

  /**
   * Valores para $tipo
   * 
   * 1 : Se envia objeto emergencia como parametro
   * 2 : Se envia NULL para realizar busqueda del paciente
   */
  function getReniecByDni_paciente(tipo, valor_parametro) {
    limpiar_paciente(); 
    
    $('#tipodoc_beneficiario_bus').prop('disabled', true);
    $('#ndoc_beneficiario_bus').prop('disabled', true);
    $('#btnBuscarDniPaciente').prop('disabled', true);

    let tipodoc_beneficiario = 0;
    let dni_paciente = "";

    if (tipo == '1') {
      tipodoc_beneficiario = valor_parametro.id_tipo_doc_ident;
      dni_paciente = valor_parametro.nro_doc_ident;
    } else if (tipo == '2') {
      tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
      dni_paciente = $("#ndoc_beneficiario_bus").val();
    }

    var paciente_no_existe=false;
    $.ajax({ 
      url: "{{ route('obtener_datos_paciente_ajax') }}",         
      type: "GET",
      dataType: "JSON",
      data: {           
        dni: dni_paciente,
        tipo_documento:tipodoc_beneficiario      
      },
			beforeSend: function() {
				swal({
					buttons: false,
					title: "Realizando Consulta ...",
					text: "Espere un momento mientras se genera la solicitud.",
					icon: "warning",
          buttons: false,
          closeOnClickOutside: false,
          closeOnEsc: false
				});
			},
      success: function(result) {  
        console.log("result:result:result:result:: "+result);
        if(result!=null){
          if (result[0] != undefined) { 
             paciente_no_existe=true;

          }else if(result.id>0 && result.id_tipo_doc_ident>0){ //tabla asegurados 
            
            $('#dni_beneficiario').removeClass('borde-alerta');
            console.log('tabla asegurados-- removeClass'); 
            console.log('tabla asegurados--::: '+result.codigo_ubig);   
            paciente = result.paterno + ' ' + result.materno + ', ' + result.nombre;

            $('#beneficiario').val(paciente);   
            $('#id_asegurado').val(result.id);         
            $('#id_tipo_doc_ident').val(tipodoc_beneficiario);
            $('#tipo_doc_ident').val(result.tipo_doc_ident);
            $('#nro_doc_ident').val(result.nro_doc_ident);
            $('#dni_beneficiario').val(result.nro_doc_ident);
            $('#nombre_beneficiario').val(result.nombre);
            $('#paterno_beneficiario').val(result.paterno);
            $('#materno_beneficiario').val(result.materno);
            $('#sexo').val(result.sexo);
            $('#fecha_nac').val(result.fecha_nac);
            $('#parentesco_paciente').val(result.parentesco);
            $('#nro_historia').val(result.nro_historia);
            $('#tipo_beneficiario').val(result.parentesco);
            $('#edad_detallada').val(result.edad_detallada);
            $('#edad').val(result.edad);

            
            if (result.id_raza != null) $('#id_etnia').val(result.id_raza).change();
            if (result.id_tipo_sangre != null) $('#id_tipo_sangre').val(result.id_tipo_sangre).change();
            if (result.id_estado_civil != null) $('#id_estado_civil').val(result.id_estado_civil).change();
            if (result.id_religion != null) $('#id_religion').val(result.id_religion).change();
            if (result.id_grado_instruccion != null) $('#id_grado_instruccion').val(result.id_grado_instruccion).change();
            if (result.id_ocupacion != null) $('#id_ocupacion').val(result.id_ocupacion).change();

            // Ubigeo Procedencia
            if (result.id_distrito != null) {
              $('#id_departamento_domicilio').val(result.id_departamento).change();
              $('#id_provincia_domicilio').val(result.id_provincia).change();
              $('#id_distrito_domicilio').val(result.id_distrito).change();
            }
            $('#direccion_domicilio').val(result.domicilio);

            // Ubigeo domicilio
            if (result.id_distrito_procedencia != null) {
              $('#id_departamento_procedencia').val(result.id_departamento_procedencia).change();
              $('#id_provincia_procedencia').val(result.id_provincia_procedencia).change();
              $('#id_distrito_procedencia').val(result.id_distrito_procedencia).change();
            }
            $('#direccion_procedencia').val(result.direccion_procedencia);

            $('#id_situacion').val('');  
            $('#situacion_paciente').val('');  
            $('#nombre_titular').html(''); 
            $('#sexo_titular').html('');  
            $('#dni_titular').html('');  
            $('#grado_titular').html(''); 
            $('#situacion_titular').html('');
            $('#unidad_titular').html(''); 
            $('#dni_de_titular').val('');  
            $('#grado_de_titular').val(''); 
            $('#situacion_de_titular').val('');   
            $('#unidad_de_titular').val(''); 

            if (result.situacion_titular != undefined) {
              $("#tab1DatosPacienteTitularLI").show();
              $('#dni_titular').html(result.nrodoctitular); 
              $('#dni_de_titular').val(result.nrodoctitular);
              $('#nombre_titular').html(result.apepattitular+' '+result.apemattitular+' '+result.nomtitular);  
              $('#grado_titular').html(result.grado_titular); 
              $('#grado_de_titular').html(result.grado_titular);
              $('#situacion_titular').html(result.situacion_titular); 
              $('#situacion_de_titular').val(result.situacion_titular); 
              $('#unidad_titular').html(result.unidad_pnptitular);
              $('#unidad_de_titular').val(result.unidad_pnptitular); 
            }
            
            //obtenemos la foto
            if(result.foto != undefined && result.foto!=null){  
              $('#foto_paciente').val(result.foto);  
              var imagen = document.getElementById("foto_imagen_paciente");
              imagen.src = "data:image/jpeg;base64," + result.foto;
            }

            if (result.edad < 18 || result.edad >= 60) $("#panel_apoderado").show();
            else $("#panel_apoderado").hide();

            if (result.edad < 18) $("#menor_mayor_edad").val("menor");
            else if (result.edad >= 60) $("#menor_mayor_edad").val("mayor");

            swal({
              buttons: false,
              title: "Datos Correctos",
              text: "Los Datos fueron cargado de forma correcta.",
              icon: "success",
              timer: 1500,
            });

            if (tipo == '1') {
              cargar_datos_desde_emergencia(valor_parametro);
            }
          }

        }

      }, 
      error: function(xhr, status) {
        $("#tab1DatosPacienteTitularLI").hide();
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Ha ocurrido un error al consultar el N° Documento del paciente.",
          icon: "error",
          timer: 2500,
        });
      }, 
      complete : function(xhr, status) {
        $('#tipodoc_beneficiario_bus').prop('disabled', false);
        $('#ndoc_beneficiario_bus').prop('disabled', false);
        $('#btnBuscarDniPaciente').prop('disabled', false);
      }
    });
  }

  $(document).ready(function() { 
    
    // $("#tab1DatosPacienteTitularLI").hide();
    // validar_campos_solo_numerico('telefono');
    // validar_campos_solo_numerico('telefono2');

    $('#dni_beneficiario').focus();
     
    $('.fecha').datepicker({
        language: "es",
        format: "dd/mm/yyyy",
        autoclose: true
    });
    

    $("#selectSexo").hide();
    $("#fecha_nacimiento_editable").hide();
    
    $("#sexo").change(function(e) {
      var sexo= $('#sexo').val();
      if(sexo=="F"){
        $('#frecuencia_fetal').show();
      }else{
        $('#frecuencia_fetal').hide(); 
      }
    }); 

    $('#fecha_nac2').change(function(){ 
      var fechaIni = $('#fecha_nac2').val();
      f1 = fechaIni.split("/");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59)); 
      if (f1 > f3) {
        alert('La Fecha de Nacimiento debe ser menor o igual a la fecha Actual.');
        $('#fecha_nac2').val("");
        $('#fecha_nac2').focus();
      }    
    }); 

        
    if($obtener_id_de_emergencia==''){
        console.log('SOLO NUEVOSSSSSSSSSSSSSSSSSSSSSS'); 
        
    } else { 
        swal({
          buttons: false,
          title: "Realizando Consulta ...",
          text: "Realizando Importación de Datos, espere un momento por favor.",
          icon: "warning",
                  buttons: false,
                  closeOnClickOutside: false,
                  closeOnEsc: false
        });
        /*  
        */
        ///traemos la trama de emergencia
        $('#id_emergencia').val($obtener_id_de_emergencia);
        setTimeout(function() {  
            $('#dni_beneficiario').val(""); 
            $.ajax({
            url:'{{url('admision_hospitalizacion_nuevo_trama/')}}/'+$obtener_id_de_emergencia, // select a toda la info de emergencia
            type:'GET',
            dataType:'JSON',
                success: function(data){  
                    console.log(data); 
                    if(data!=null){ 
                      $('#dni_beneficiario').val(data.dni_beneficiario);  
                      ////RECIBE EL PARAMETRO PARA OBTENER TRAMA DE EMERGENCIA
                      getReniecByDni_paciente("1", data); 

                    }
                }
            })
        }, 3000);     
    }

    $('#id_tipo_apoderado').val('8').trigger('change');
    $('#id_tipo_acompaniante').val('8').trigger('change');
    
  });  
 
//VALIDAR FECHA ACTUAL CON EL EVENTO SPLIT
//
$('#hora').on('change', function (e) {
    const hora = e.target.value;
    const horaIngreso = new Date();
    const horasMinutos = hora.split(':');
    horaIngreso.setHours(horasMinutos[0]);
    horaIngreso.setMinutes(horasMinutos[1]);
    // fecha_alta_medica ESTOS ES EL NOMBRE DEL INPUT DEL INPUT FECHA_ALTA
    // Obtener la fecha_alta del input field
    const fechaAlta = $('#fecha_alta_medica').val();
    const fechaAltaDate = fechaAlta ? new Date(fechaAlta) : null; // aqui es para saber si la data es nulla

    // Obtener la hora actual
    const horaActual = new Date();
    const horaActualFormatted = horaActual.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'});

    // Comprobar si la fecha_alta es igual a la fecha actual
    if (fechaAltaDate && fechaAltaDate.toDateString() === horaActual.toDateString()) {
        // Si la fecha_alta fechaAltaDate es igual a la fecha actual horaActual, compara la hora de ingreso , la hora ingreso es cuando seleccionar el reloj del input, con la hora alta y la hora actual
        //horaalta es horaAltaDate 
        //hora_alta_medica ESTO ES EL NOMBRE DEL INPUT HORA_ALTA
        const horaAlta = $('#hora_alta_medica').val();
        
        const horaAltaParts = horaAlta.split(':');
        const horaAltaDate = new Date();
        horaAltaDate.setHours(horaAltaParts[0]);
        horaAltaDate.setMinutes(horaAltaParts[1]);

        // Comprobar si la horaIngreso está fuera del rango entre hora_alta y hora_actual
        if (horaIngreso < horaAltaDate || horaIngreso > horaActual) {
            Swal.fire({
                title: "<strong><u>ALERTA HOSPITALIZACION</u></strong>",
                icon: "info",
                html: `La hora de ingreso debe estar dentro del rango entre ${horaAlta} y la hora actual (${horaActualFormatted})`,
                confirmButtonText: 'OK',
                preConfirm: function() {
                    $('#hora').val("");
                    $('#hora').focus();
                }
            });
        }
    } else {
        // Si la fecha_alta no es igual a la fecha actual, solo compara la hora de ingreso con la hora actual
        if (horaIngreso > horaActual) {
            Swal.fire({
                title: "<strong><u>ALERTA HOSPITALIZACION</u></strong>",
                icon: "info",
                html: `La hora de ingreso debe ser igual o menor a la hora actual (${horaActualFormatted})`,
                confirmButtonText: 'OK',
                preConfirm: function() {
                    $('#hora').val("");
                    $('#hora').focus();
                }
            });
        }
    }
});

  function cargar_datos_desde_emergencia(data_emergencia){
    if($obtener_id_de_emergencia==0 ||$obtener_id_de_emergencia==null ||$obtener_id_de_emergencia==''){ 
      console.log('SOLO NUEVO');
      setTimeout(function() {
        var edad = $("#edad").val();
        console.log('SOLO NUEVOSSSSSSSSSSSSSSSSSSSSSS edad: '+edad); 
        if(edad>=60){
            // consultar_copia_de_responsable();
        }
      }, 2000);    
                      
      console.log('SOLO NUEVO');
    } else {
        // setTimeout(function() {
          
          $('#id_topico').val(data_emergencia.id_triaje);  
          $('#id_prioridad').val(data_emergencia.id_prioridad); 
          $('#id_upss_encargado').val(data_emergencia.id_upss_destino);
          $('#descripcion_upss_encargado').val(data_emergencia.descripcion_upss_destino);
          $('#id_servicio_encargado').val(data_emergencia.id_servicio_destino);
          $('#nombre_servicio_encargado').val(data_emergencia.nombre_servicio_destino);

          // getCama2();

          //$('#id_prioridad_descripcion').val(data_emergencia.descripcion_prioridad + "/"+ data_emergencia.descripcion_topico);
          //$('#dni_beneficiario').val(data_emergencia.dni_beneficiario);  
          $("#id_campo_busqueda_medico").val(data_emergencia.id_medico);
          $("#nro_doc_medico_detallado").val('DNI '+data_emergencia.medico_ndocumento );
          $("#profesional_medico").val(data_emergencia.medico_paterno + " "+ data_emergencia.medico_materno+ " "+ data_emergencia.medico_nombre);
          $("#especialidad_medico").val(data_emergencia.medico_profesion);
          $("#cmp_medico").val(data_emergencia.medico_colegiatura);
          $("#rne_medico").val(data_emergencia.medico_rne);
          $("#nro_doc_medico").val(data_emergencia.medico_ndocumento);


        var institucion='';
        ////VERIFICAMOS SI ES UN REFEREIDO
        /*console.log("data.evacuado_unidad_policial::::: "+data_emergencia.evacuado_unidad_policial);
        if(data_emergencia.evacuado_unidad_policial!=null){ 
          // habiliar_referencia.checked = true;
          
          // $('#es_referido').val("1");
          $("#seccion_de_referencia").show();
          $('#id_ipress_origen_referencia').val("");
          $('#descripcion_ipress_origen_referencia').val("");
          $('#descripcion_ipress_origen_referencia').focus();
          
          $('#numero_de_referencia').val(data_emergencia.evacuado_numero_oficio);  
        
          setTimeout(function() {  
            $('#id_ipress_origen_referencia').val(""); 
            $('#descripcion_ipress_origen_referencia').val(""); 

            console.log('#habiliar_no_pnp'); 
            if ($('#habiliar_no_pnp').prop('checked')) {
              institucion='-';
            } else {
              console.log('El checkbox está marcado');
              institucion='x_institucion';
            }   
          }, 1000);  
        }*/

        $id_diag_cab = 0;
        if(data_emergencia.id_diag_cab != null && data_emergencia.id_diag_cab != 0){
          $id_diag_cab = data_emergencia.id_diag_cab;
        }

        $id_diag_cuer1 = 0;
        if(data_emergencia.id_diag_cuer1 != null && data_emergencia.id_diag_cuer1 != 0){
          $id_diag_cuer1 = data_emergencia.id_diag_cuer1;
        }

        $id_diag_cuer2 = 0;
        if(data_emergencia.id_diag_cuer2 != null && data_emergencia.id_diag_cuer2 != 0){
          $id_diag_cuer2 = data_emergencia.id_diag_cuer2;
        }

        if(data_emergencia.id_diag_cab != null && data_emergencia.id_diag_cab != 0){
          AddDiagnostico(data_emergencia.id_diag_cab);
        } 


        if($id_diag_cab!=0 && $id_diag_cuer1==0 && $id_diag_cuer2==0){
          swal({
            buttons: false,
            title: "Datos cargados Correctamente",
            text: "Los diagnósitcos fueron cargados Exitosamente.",
            icon: "success",
            timer: 1500,
          }); 
            
          setTimeout(function() {
            var edad = $("#edad").val();
            if(edad>=60){
              // consultar_copia_de_responsable();
            }
          }, 2200); 
        }
 
        setTimeout(function() {
          if(data_emergencia.id_diag_cuer1 != null && data_emergencia.id_diag_cuer1 != 0){
            AddDiagnostico(data_emergencia.id_diag_cuer1);
          }
        }, 3500);
 
        setTimeout(function() {
          if($id_diag_cab!=0 && $id_diag_cuer1!=0 && $id_diag_cuer2==0){
            swal({
              buttons: false,
              title: "Datos cargados Correctamente",
              text: "Los diagnósitcos fueron cargados Exitosamente.",
              icon: "success",
              timer: 1500,
            }); 
            
            setTimeout(function() {
              var edad = $("#edad").val();
              if(edad>=60){
                // consultar_copia_de_responsable();
              }
            }, 2200);  
            
          }
        }, 5700);
 
        setTimeout(function() {
          if(data_emergencia.id_diag_cuer2 != null && data_emergencia.id_diag_cuer2 != 0){
            AddDiagnostico(data_emergencia.id_diag_cuer2);
          }
        }, 5500); 
 
        setTimeout(function() {
          if($id_diag_cab!=0 && $id_diag_cuer1!=0 && $id_diag_cuer2!=0){ 
            swal({
              buttons: false,
              title: "Datos cargados Correctamente",
              text: "Los diagnósitcos fueron cargados Exitosamente.",
              icon: "success",
              timer: 1500,
            }); 
            
            setTimeout(function() {
              var edad = $("#edad").val();
              if(edad>=60){
                // consultar_copia_de_responsable();
              }
            }, 2200);  
            
          }
        }, 5800); 

    }
  }

  function completarConCeros(numero, longitud) {
    let numeroString = numero.toString();
    while (numeroString.length < longitud) {
      numeroString = '0' + numeroString;
    }
    return numeroString;
  }

  function guardarNueva_admision_hospitalizacion(){ 
      
    var fecha_atencion = $("#fecha_atencion").val();
    var hora = $("#hora").val();
    var cod_siteds= $('#cod_siteds_susalud').val();  
    var id_asegurado = $("#id_asegurado").val().trim();
    let menor_mayor_edad = $("#menor_mayor_edad").val();
    let id_tipo_apoderado = $("#id_tipo_apoderado").val();
    // let nro_doc_apoderado2 = $("nro_doc_apoderado2").val();
    // let nombre_apoderado2 = $("nombre_apoderado2").val();
    // let telefono_apoderado2 = $("telefono_apoderado2").val();

    // let nro_doc_apoderado_mama = $("nro_doc_apoderado_mama").val();
    // let nombre_apoderado_mama = $("nombre_apoderado_mama").val();
    // let telefono_apoderado_mama = $("telefono_apoderado_mama").val();

    let id_tipo_acompaniante = $("#id_tipo_acompaniante").val();
    // let nro_doc_acompaniante = $("nro_doc_acompaniante2").val();
    // let nombre_acompaniante = $("nombre_acompaniante2").val();
    // let telefono_acompaniante = $("telefono_acompaniante2").val();

    let id_etnia = $('#id_etnia').val();
    let id_tipo_sangre = $('#id_tipo_sangre').val();
    let id_estado_civil = $('#id_estado_civil').val();
    let id_religion = $('#id_religion').val();
    let id_grado_instruccion = $('#id_grado_instruccion').val();
    let id_ocupacion = $('#id_ocupacion').val();
    let id_distrito_nacimiento = $('#id_distrito_nacimiento').val();
    let direccion_nacimiento = $('#direccion_nacimiento').val();
    let id_distrito_procedencia = $('#id_distrito_procedencia').val();
    let direccion_procedencia = $('#direccion_procedencia').val();
    let id_distrito_domicilio = $('#id_distrito_domicilio').val();
    let direccion_domicilio = $('#direccion_domicilio').val();

    var id_campo_busqueda_medico = $('#id_campo_busqueda_medico').val(); // Id profesional
    var id_consultorio = $('#id_consultorio').val(); // Especialidad
    var id_sala = $('#id_sala').val();
    var id_cama = $('#id_cama').val();

    var msg = "";

    if (fecha_atencion == "") msg += "Falta ingresar la <b>Fecha de Ingreso</b><br>";
    if (hora == "") msg += "Falta ingresar la <b>Hora de Ingreso</b>.<br>";
    if (hora != "") {
      let fechaIngreso = new Date(fecha_atencion);
      let fechaActual = new Date();

      if (fechaIngreso.toDateString() === fechaActual.toDateString()) {
        let horaActual = new Date().toLocaleTimeString('es-PE', {hour12: false, hour: '2-digit', minute: '2-digit'});
        if (hora > horaActual) {
          msg += "La <b>Hora de Ingreso</b> no puede ser mayor que la Hora Actual.<br>";
        }
      }
    }
    if (id_asegurado == "") msg += "Falta seleccionar al <b>Paciente</b>.<br>";
    if (cod_siteds == "") msg += "Falta ingresar el <b>Código SITEDS</b>.<br>";
    else $("#cod_siteds_susalud").val(completarConCeros(cod_siteds,12));
    if (id_etnia == "0") msg += "Falta ingresar la <b>Etnia</b>.<br>";
    if (id_tipo_sangre == "0") msg += "Falta ingresar el <b>Tipo de sangre</b>.<br>";
    if (id_estado_civil == "0") msg += "Falta ingresar el <b>Estado civil</b>.<br>";
    if (id_religion == "0") msg += "Falta ingresar la <b>Religión</b>.<br>";
    if (id_grado_instruccion == "0") msg += "Falta ingresar el <b>Grado de Instrucción</b>.<br>";
    if (id_ocupacion == "0") msg += "Falta ingresar la <b>Ocupación</b>.<br>";
    if (id_distrito_nacimiento == "0" || id_distrito_nacimiento == null) msg += "Falta seleccionar el <b>Distrito de Nacimiento</b>.<br>";
    if (direccion_nacimiento.trim() == "") msg += "Falta ingresar la <b>Dirección de Nacimiento</b>.<br>";
    if (id_distrito_procedencia == "0" || id_distrito_procedencia == null) msg += "Falta seleccionar el <b>Distrito de Procedencia</b>.<br>";
    if (direccion_procedencia.trim() == "") msg += "Falta ingresar la <b>Dirección de Procedencia</b>.<br>";
    if (id_distrito_domicilio == "0" || id_distrito_domicilio == null) msg += "Falta ingresar el <b>Distrio de Domicilio</b>.<br>";
    if (direccion_domicilio.trim() == "") msg += "Falta ingresar la <b>Dirección de Domicilio</b>.<br>";
    if (id_campo_busqueda_medico == "") msg += "Falta seleccionar el <b>Profesional de Salud</b>.<br>";
    if (id_consultorio == "0") msg += "Falta seleccionar la <b>Especialidad</b>.<br>";
    if (id_sala == "0") msg += "Falta seleccionar el <b>Servicio</b>.<br>";
    if (id_cama == "0" || id_cama == null) msg += "Falta seleccionar la <b>Cama</b>.<br>";
    if (msg != "") {
      bootbox.alert(msg);
      $("#btn_generate_siteds").prop("disabled", false);
      /*console.log(msg);
      swal({
        buttons: false,
        title: "Mensaje de Error",
        text: msg,
        icon: "error",
        timer: 10000,
      });   */
      return;
    } else {

      ////VALIDAMOS EL DETALLE DE DIAGNOSTICOS, TANTO FILAS COMO CAMPOS LLENOS 
      let ind_diag = $('#tblDiagnostico tbody tr').length;
      if(ind_diag > 0){
        fila_dignosticos=$('#tblDiagnostico tbody').children('tr');
        for (i = 0; i < ind_diag; i++) {
          fila_dignostico=fila_dignosticos[i];
          let id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
          let id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
          if(id_diagnostico == ""){
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "Debe ingresar el nombre de diagnostico.",
              icon: "error",
              timer: 2500,
            });   
            return;
          }
          if(id_tipo_diagnostico == "0"||id_tipo_diagnostico == ""||id_tipo_diagnostico == null) {
            swal({
              buttons: false,
              title: "Mensaje de Error",
              text: "Debe ingresar el tipo de diagnostico.",
              icon: "error",
              timer: 2500,
            });     
            return;
          }
        }
      }else{
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Debe ingresar minimo un diagnostico.",
          icon: "error",
          timer: 2500,
        });          
        return;
      }

      ///PROCESAR EL GUARDADO DE REGISTRO
      var codSiteds= $('#cod_siteds_susalud').val();  
      $.ajax({
        url:'{{url('validar_dato_siteds_emergencia/')}}/'+codSiteds,
        type:'GET',
        dataType:'JSON', 
          success: function(data){   
            if(data!=null){ 
              console.log('data[0].cantidad_siteds:'+ data.cantidad_siteds); 
              if(data.cantidad_siteds!=0){ 
                
                swal({
                  buttons: false,
                  title: "Mensaje de Error",
                  text: "El CÓDIGO SITEDS ingresado ya fue asignado a una admisión diferente, por favor ingrese uno diferente.",
                  icon: "error",
                  timer:3000,
                });  

                cambiar_de_PestanaTab("tab0DatosPaciente");
                setTimeout(function (){ 
                    $('#cod_siteds_susalud').val('');
                    $('#cod_siteds_susalud').focus();
                }, 500);  

              } else{ 
                submitFrm();
                return false;
              } 
            } else{ 
              submitFrm();
              return false;
            }
          }
      });  
    }
     
  }

    function submitFrm() {
      bootbox.confirm({
          message: "¿Seguro de Guardar la Admisión a Hospitalización?",
          buttons: {
              confirm: {
                  label: 'Si',
                  className: 'btn-success'
              },
              cancel: {
                  label: 'No',
                  className: 'btn-danger'
              }
          },
          callback: function (result) {
              if (result==true) {
                  $("#frmHospitalizacion").submit();
              } else {
                  $('#btnReg').prop('disabled', false);
              }
          }
      });
    }

    function getSala(){
      $('#id_servicio_encargado').html('');
      var id_upss = $('#id_upss_encargado').val();
      $.ajax({
        url: '/obtener_servicio_by_id_upss/'+ id_upss,
        success: function(result){                    
          let newOption = "<option value='0'>--SELECCIONAR--</option>"; 
          if (result.length === 1) {
            newOption += "<option value='"+result[0].id+"' selected>"+result[0].servicio+"</option>";
          } else {
            $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"'>"+oo.servicio+"</option>";
            });
          }
          $('#id_servicio_encargado').html(newOption).change();
          $('#id_servicio_encargado').focus();
        }
      }); 
    }
   

  function getCama2(){
    $('#id_cama').html('').change();
    let id_establecimiento = $('#id_establecimiento').val();
    let nombre_tipo_atencion = 'HOSPITALIZACION';
    let id_servicio_destino = $('#id_servicio_destino').val();
    $.ajax({
      url: '/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto/' + id_establecimiento + '/' + nombre_tipo_atencion + '/' + id_servicio_destino,
      success: function(result){
        let newOption = "<option value='0'>--SELECCIONAR--</option>";
        $(result).each(function (ii, oo) {
          newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
        });
        $('#id_cama').html(newOption);
        $("#id_cama").val('0').trigger("change");
      }
    });
  }
    
    $('#addDiagnostico').on('click',function(){
      var sexo	= $('#sexo').val();
      if(sexo==''){
          bootbox.alert("Falta asignar un Paciente");
          $('#btnReg').prop('disabled', false);
          return false;
      }else{
        AddDiagnostico(0);
      }
    });
    
    $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
   
 function AddDiagnostico(valor_id) {
  var dni_beneficiario = $('#dni_beneficiario').val();
  if (dni_beneficiario == null || dni_beneficiario === "") {
    // Mostrar un mensaje de error al usuario
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Por favor, ingrese un número de documento válido al paciente antes de agregar un diagnóstico.",
          icon: "error",
          timer: 1500,
        });     
    return; // Detener la ejecución de la función
  }

    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length; 
    if (ind > 0) {
        $('.id_diagnostico').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });

        fila_medicamentos = $('#tblDiagnostico tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento = fila_medicamentos[idval];
    }

    var item_tipo_diagnostico = ''; 
    
    if(valor_id!=0 && valor_id!=null){
      item_tipo_diagnostico += '<option value="">--SELECCIONAR--</option>';
      item_tipo_diagnostico += '<option value="1" >PRESUNTIVO</option>';
      item_tipo_diagnostico += '<option value="2" selected>DEFINITIVO</option>';
      item_tipo_diagnostico += '<option value="3">REITERATIVO</option>'; 
    }else{
      item_tipo_diagnostico += '<option value="">--SELECCIONAR--</option>';
      item_tipo_diagnostico += '<option value="1">PRESUNTIVO</option>';
      item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';
      item_tipo_diagnostico += '<option value="3">REITERATIVO</option>'; 
    }

    newRow += '<tr>';
    newRow +='<td><input type="text"  name="nombre_diagnostico[]" required="" id="nombre_diagnostico' + ind + '" class="form-control input-sm" style="margin-left:4px; width:100%; text-align:left;height: 28px !important;" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind + '" name="id_diagnostico[]" value="" data-id='+ind+'></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind + '" name="id_tipo_diagnostico[]" style="margin:0 20px;width:150px; font-size: 12px !important;cursor:pointer;">' + item_tipo_diagnostico + '</select></td>';
    // newRow += '<input type="text" value="DEFINITIVO" class="form-control input-sm" id="tipo_diagnostico' + ind + '"></td>';
    newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px" id="btnDelete' + ind + '"><i class="fa fa-times"></i></button></td>';
    newRow += '</tr>';

    var filas = $('#tblDiagnostico tbody tr');
    var ultimaFila = filas.last();
    var primeraFila = filas.first();
    if (primeraFila.find('input[name="nombre_diagnostico"]').val() == "" || primeraFila.find('select[name="id_tipo_diagnostico"]').val() == "") {
      // Mostrar un mensaje de advertencia al usuario 
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Por favor, complete el nombre y el tipo de diagnóstico en la primera fila antes de agregar una nueva.",
          icon: "error",
          timer: 1500,
        });    
      return; // Detener la ejecución de la función
    } else if (primeraFila.find('input,select').filter(function() { return $(this).val() == ""; }).length > 0) {
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Por favor, complete la primera fila de la tabla de diagnósticos antes de agregar una nueva.",
          icon: "error",
          timer: 1500,
        });    
      return; // Detener la ejecución de la función
    } else if (ultimaFila.find('input,select').filter(function() { return $(this).val() == ""; }).length > 0) {
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Por favor, complete la última fila de la tabla de diagnósticos antes de agregar una nueva.",
          icon: "error",
          timer: 1500,
        });   
      return; // Detener la ejecución de la función
    }
             

      var sexo = $('#sexo').val();
      if (sexo == 'M') {
        genero = 1;
      } else {
        if (sexo == 'F') {
          genero = 2;
        }
      }

    $url_consulta=''; 
    if(valor_id!=0 && valor_id!=null){ 
      $url_consulta='../obtener_diagnostico_genero_by_id/' + valor_id ;
          $.ajax({
            url: $url_consulta,
            dataType: "json", 
            beforeSend: function() {
              swal({
                buttons: false,
                title: "Consultando Diagnósticos",
                text: "Espere un momento mientras se genera la solicitud.",
                icon: "warning",
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false
              });
            },
            success: function(data) { 
              if(data!=null){ 
                $('#tblDiagnostico tbody').append(newRow); 
                $('#nombre_diagnostico' + ind).focus();
                
                $("#id_diagnostico" + ind).val(data.id);
                $("#nombre_diagnostico" + ind).val(data.codigo + ' - ' + data.nombre);
                $("#nombre_diagnostico" + ind).prop("readonly", true);
                $("#cmbTipoDiagnostico" + ind).prop("disabled", true);
                $("#btnDelete" + ind).hide();
              }
            }
          });
    }else{

      $('#tblDiagnostico tbody').append(newRow); 
      $('#nombre_diagnostico' + ind).focus();

          
      $("#nombre_diagnostico" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          $url_consulta='../obtener_diagnostico_genero/' + request.term + '/' + genero;
          $.ajax({
            url: $url_consulta,
            dataType: "json",
            success: function(data) { 
                let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function(){return parseInt($(this).val());}).get();
                let filtro = data.filter((element)=>!listaDiagnosticoSeleccionado.includes(element.id));
                // Limitar la cantidad de resultados a mostrar
                var limitedData = filtro.slice(0, 20); 
                response(limitedData);  
            }
          })
        },
        
        select: function(e, ui) { 
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico" + ind).val(id_diagnostico);
            $("#nombre_diagnostico" + ind).prop("readonly", true); 
            $("#cmbTipoDiagnostico" + ind).focus(); 
        }
      });
    }
 

  $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
    $('#addDiagnostico').focus();
  }); 
  
  $("#cmbTipoDiagnostico" + ind).change(function(){ 
    $('#addDiagnostico').focus();
  });
  
}  
  
  function getProfesional() {
    let id_medico = $('#campo_busqueda_medico').val();
    $.ajax({
      url:'{{url('parametro/profesional/by_id/')}}/'+ id_medico,
      type:'GET',
      dataType:'JSON',
      success: function(data){
        if(data!=null){
          if(data != undefined && data != null){
            $("#id_campo_busqueda_medico").val(data.id);
            $("#nro_doc_medico_detallado").val('DNI '+data.dni );
            $("#profesional_medico").val(data.paterno + " "+ data.materno+ " "+ data.nombre);
            $("#especialidad_medico").val(data.profesion);
            $("#cmp_medico").val(data.colegiatura);
            $("#rne_medico").val(data.rne);
            $("#nro_doc_medico").val(data.dni);
          }
        }
      }
    });
  }

  // TAGSITEDS
  function generarCodigoSiteds() {
    $("#btn_generate_siteds").prop("disabled", true);
    $("#cod_siteds_susalud").prop("readonly", true);
    Swal.fire ({
      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
      html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onBeforeOpen: () => {
        Swal.showLoading ()
      },
    })
    const establecimiento = {!! json_encode($establecimiento) !!}
    const codigo_ipress = establecimiento && establecimiento.codigo ? establecimiento.codigo : '00013591';
    const id_servicio_destino = $("#id_servicio_destino").val();
    const nro_doc_ident = $("#nro_doc_ident").val();
    const body = {
      "idRemitente": codigo_ipress,
      "nuRucRemitente": "",
      "idCorrelativo": '00000001',
      "nuDoPaciente": nro_doc_ident,
      "noResponsableAut": "",
      "noPaResponsableAut": "",
      "noMaResponsableAut": "",
      "nuDoResponsableAut": "",
      "coAdmisionista": nro_doc_ident,
      "beMaxInicial": "0",
      "coTiMoneda": "1",
      "coPagoFijo": "0",
      "coCalServicio": "VS",
      "coPagoVariable": "100",
      "flagCaGarantia": "0",
      "deFlagCartaGarantia": "Sigesapol v0.1",
      "tipoAtencionId": "20",
      "servicioId": id_servicio_destino
    }
    $.ajax({
      url:'/ws-dirsapol-siteds',
      type:'POST',
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(body),
      success: (result) => {
        $("#cod_siteds_susalud").val(result.nuAutorizacion)
        Swal.fire({
          icon: "success",
          title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
          html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${result.message || " Operación Exitosa"}</span></div>`,
          confirmButtonText: 'ENTENDIDO',
          allowOutsideClick: false,
          allowEscapeKey: false,
          customClass: 'swal-wide',
        });
      },
      error: function(xhr, status, error){
        $("#cod_siteds_susalud").val("");
        $("#cod_siteds_susalud").prop("readonly", false); 
        $("#btn_generate_siteds").prop("disabled", false);
        const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
        Swal.fire({
          icon: 'error',
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
          html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
          confirmButtonText: 'ENTENDIDO',
          customClass: 'swal-wide',
        });
      }, 
      complete: function(xhr, status) {
        Swal.hideLoading();
      }
    })
  }

  function onClickBtnGenerateSiteds() {
    const nro_doc_ident = $("#nro_doc_ident").val();
    const id_servicio_destino = $("#id_servicio_destino").val();
  
    let messageValidate = "";
    if (!nro_doc_ident || nro_doc_ident.length < 8) {
        messageValidate += `<p> - Es necesario ingresar el N° DOCUMENTO</p>`;
    }
    if (!id_servicio_destino || parseInt(id_servicio_destino) === 0) {
        messageValidate += `<p> - Es necesario seleccionar el SERVICIO </p>`;
    }
    if (messageValidate !== null && messageValidate.length > 0) {
        Swal.fire({
            icon: "error",
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
            html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${messageValidate}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
            customClass: 'swal-wide',
        });
        return
    }
  }
  
  function getProvinciaNacimiento() {
    $('#id_provincia_nacimiento').html('').change();
    $('#id_distrito_nacimiento').html('').change();
    let id_departamento_nacimiento = $('#id_departamento_nacimiento').val();

    if (id_departamento_nacimiento > 0) {
      $.ajax({    
          url: '/obtener_provincia_por_departamento/' + id_departamento_nacimiento,
          async: false,
          success: function(result) {
            let newOption = "<option value='0'>--SELECCIONE--</option>";
            if (result.length === 1) {
              newOption += "<option value='" + result[0].id_provincia + "' selected>" + result[0].descripcion + "</option>";
            } else {
              $(result).each(function (ii, oo) {
                newOption += "<option value='" + oo.id_provincia + "'>" + oo.descripcion + "</option>";
              });
            }
            $('#id_provincia_nacimiento').html(newOption).change();
          }
      });
    }
  }

  function getDistritoNacimiento() {
    $('#id_distrito_nacimiento').html('').change();
    let id_provincia_nacimiento = $('#id_provincia_nacimiento').val();

    if (id_provincia_nacimiento > 0) {
      $.ajax({    
          url: '/obtener_distrito_por_provincia/' + id_provincia_nacimiento,
          async: false,
          success: function(result) {
            let newOption = "<option value='0'>--SELECCIONE--</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='" + oo.id_distrito+"'>" + oo.descripcion + "</option>";
            });
            $('#id_distrito_nacimiento').html(newOption).change();
          }
      });
    }
  }

  function getProvinciaProcedencia() {
    $('#id_provincia_procedencia').html('').change();
    $('#id_distrito_procedencia').html('').change();
    let id_departamento_procedencia = $('#id_departamento_procedencia').val();

    if (id_departamento_procedencia > 0) {
      $.ajax({    
          url: '/obtener_provincia_por_departamento/' + id_departamento_procedencia,
          async: false,
          success: function(result) {
            let newOption = "<option value='0'>--SELECCIONE--</option>";
            if (result.length === 1) {
              newOption += "<option value='" + result[0].id_provincia + "' selected>" + result[0].descripcion + "</option>";
            } else {
              $(result).each(function (ii, oo) {
                newOption += "<option value='" + oo.id_provincia + "'>" + oo.descripcion + "</option>";
              });
            }
            $('#id_provincia_procedencia').html(newOption).change();
          }
      });
    }
  }

  function getDistritoProcedencia() {
    $('#id_distrito_procedencia').html('').change();
    let id_provincia_procedencia = $('#id_provincia_procedencia').val();

    if (id_provincia_procedencia > 0) {
      $.ajax({    
          url: '/obtener_distrito_por_provincia/' + id_provincia_procedencia,
          async: false,
          success: function(result) {
            let newOption = "<option value='0'>--SELECCIONE--</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='" + oo.id_distrito+"'>" + oo.descripcion + "</option>";
            });
            $('#id_distrito_procedencia').html(newOption).change();
          }
      });
    }
  }

  function getProvinciaDomicilio() {
    $('#id_provincia_domicilio').html('').change();
    $('#id_distrito_domicilio').html('').change();
    let id_departamento_domicilio = $('#id_departamento_domicilio').val();

    if (id_departamento_domicilio > 0) {
      $.ajax({    
          url: '/obtener_provincia_por_departamento/' + id_departamento_domicilio,
          async: false,
          success: function(result) {                    
            let newOption = "<option value='0'>--SELECCIONE--</option>";
            if (result.length === 1) {
              newOption += "<option value='" + result[0].id_provincia + "' selected>" + result[0].descripcion + "</option>";
            } else {
              $(result).each(function (ii, oo) {
                newOption += "<option value='" + oo.id_provincia + "'>" + oo.descripcion + "</option>";
              });
            }
            $('#id_provincia_domicilio').html(newOption).change();
          }
      });
    }
  }

  function getDistritoDomicilio() {
    $('#id_distrito_domicilio').html('').change();
    let id_provincia_domicilio = $('#id_provincia_domicilio').val();

    if (id_provincia_domicilio > 0) {
      $.ajax({    
          url: '/obtener_distrito_por_provincia/' + id_provincia_domicilio,
          async: false,
          success: function(result) {
            let newOption = "<option value='0'>--SELECCIONE--</option>";
            $(result).each(function (ii, oo) {
                newOption += "<option value='" + oo.id_distrito + "'>" + oo.descripcion + "</option>";
            });                 
            $('#id_distrito_domicilio').html(newOption).change();
          }
      });
    }
  }

  function buscar_acompaniante() {
    // limpiar_datos_acompaniante(); 
    
    $('#tipo_doc_acompaniante_busqueda').prop('disabled', true);
    $('#nro_doc_acompaniante_busqueda').prop('disabled', true);
    $('#btn_buscar_acompaniante').prop('disabled', true);

    let tipo_doc_acompaniante = $('#tipo_doc_acompaniante_busqueda').val();
    let text_tipo_doc_acompaniante = $('#tipo_doc_acompaniante_busqueda option:selected').text();
    let nro_doc_acompaniante = $('#nro_doc_acompaniante_busqueda').val();
    let url = "/ws_reniec/" + nro_doc_acompaniante;
    
    $.ajax({ 
      url: url,         
      type: "GET",
      dataType: "JSON",
			beforeSend: function() {
				swal({
					buttons: false,
					title: "Realizando Consulta ...",
					text: "Espere un momento mientras se genera la solicitud.",
					icon: "warning",
          buttons: false,
          closeOnClickOutside: false,
          closeOnEsc: false
				});
			},
      success: function(result) {
        if (result!=null) {
          if (result.codigoMensaje == "00") {
            let nombre = result.paterno + ' ' + result.materno + ', ' + result.nombres;
            $('#tipo_doc_acompaniante').val(text_tipo_doc_acompaniante);
            $('#nro_doc_acompaniante').val(result.dni);
            $('#nombre_acompaniante').val(nombre);
          }

        } else {
          swal({
            buttons: false,
            title: "Mensaje de Error",
            text: "No se ha encontrado datos con el Documento ingresado.",
            icon: "error",
            timer: 2500,
          });
        }
      }, 
      error: function(xhr, status) {
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Ha ocurrido un error al consultar el Documento ingresado.",
          icon: "error",
          timer: 2500,
        });
      },
      complete : function(xhr, status) {
        swal.close();
        $('#tipo_doc_acompaniante_busqueda').prop('disabled', false);
        $('#nro_doc_acompaniante_busqueda').prop('disabled', false);
        $('#btn_buscar_acompaniante').prop('disabled', false);
      }
    });
  }

  function buscar_apoderado() {
    // limpiar_datos_acompaniante(); 
    
    $('#tipo_doc_apoderado_busqueda').prop('disabled', true);
    $('#nro_doc_apoderado_busqueda').prop('disabled', true);
    $('#btn_buscar_apoderado').prop('disabled', true);

    let tipo_doc_apoderado_busqueda = $('#tipo_doc_apoderado_busqueda').val();
    let text_tipo_doc_apoderado_busqueda = $('#tipo_doc_apoderado_busqueda option:selected').text();
    let nro_doc_apoderado_busqueda = $('#nro_doc_apoderado_busqueda').val();
    let url = "/ws_reniec/" + nro_doc_apoderado_busqueda;
    
    $.ajax({ 
      url: url,         
      type: "GET",
      dataType: "JSON",
			beforeSend: function() {
				swal({
					buttons: false,
					title: "Realizando Consulta ...",
					text: "Espere un momento mientras se genera la solicitud.",
					icon: "warning",
          buttons: false,
          closeOnClickOutside: false,
          closeOnEsc: false
				});
			},
      success: function(result) {
        if (result!=null) {
          if (result.codigoMensaje == "00") {
            let nombre = result.paterno + ' ' + result.materno + ', ' + result.nombres;
            $('#tipo_doc_apoderado').val(text_tipo_doc_apoderado_busqueda);
            $('#nro_doc_apoderado').val(result.dni);
            $('#nombre_apoderado').val(nombre);
          }

        } else {
          swal({
            buttons: false,
            title: "Mensaje de Error",
            text: "No se ha encontrado datos con el Documento ingresado.",
            icon: "error",
            timer: 2500,
          });
        }
      }, 
      error: function(xhr, status) {
        swal({
          buttons: false,
          title: "Mensaje de Error",
          text: "Ha ocurrido un error al consultar el Documento ingresado.",
          icon: "error",
          timer: 2500,
        });
      },
      complete : function(xhr, status) {
        swal.close();
        $('#tipo_doc_apoderado_busqueda').prop('disabled', false);
        $('#nro_doc_apoderado_busqueda').prop('disabled', false);
        $('#btn_buscar_apoderado').prop('disabled', false);
      }
    });
  }

  function eventTipoApoderado() {
    let id_tipo_apoderado = $('#id_tipo_apoderado').val();

    // Cuanto el Tipo de apoderado es Solo
    if (id_tipo_apoderado == 8) $("#seccion_principal_apoderado").hide();
    else $("#seccion_principal_apoderado").show();
  }

  function eventTipoAcompaniante() {
    let id_tipo_acompaniante = $('#id_tipo_acompaniante').val();

    // Cuanto el Tipo de acompañante es Solo
    if (id_tipo_acompaniante == '8') $("#seccion_principal_acompaniante").hide();
    else $("#seccion_principal_acompaniante").show();
  }
</script>



<style>
  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }

  .swal-wide{
    width:500px !important;
  }
  hr {
    margin-top: 0 !important;
    margin-bottom: 10px;
  }
</style>

@endsection
@section('content')

<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MODULO DE ADMISION DE HOSPITALIZACIÓN</h3>
          </div>

          <div style="font-size: 11px" class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('registrar_hospitalizaciones') }}"
              id="frmHospitalizacion" name="frmHospitalizacion" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="form-group">
                <div class="col-md-4 text-center">
                  <input type="hidden" name="dni_de_titular" id="dni_de_titular">
                  <input type="hidden" name="grado_de_titular" id="grado_de_titular">
                  <input type="hidden" name="situacion_de_titular" id="situacion_de_titular">
                  <input type="hidden" name="unidad_de_titular" id="unidad_de_titular">
                  <input type="hidden" name="id_emergencia" id="id_emergencia">
                  <input type="hidden" value="{{ $id_ext_prestacion_origen }}" name="id_ext_prestacion_origen" id="id_ext_prestacion_origen">
                  <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                  <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                  <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                  <input type="hidden" value="<?php echo $nivel ?>" name="nivel" id="nivel">
                  <input type="hidden" value="<?php echo $establecimiento->id?>" id="id_establecimiento" name="id_establecimiento">
                  <input type="hidden" id="menor_mayor_edad" name="menor_mayor_edad">
                </div>
              </div>
              <fieldset>
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="row">
                      <!-- <div style="float: right;margin-right: 17px;"> -->
                        <div class="col-md-3">
                          <label>TIPO ATENCIÓN</label>
                          <input type="text" readonly="" value="<?php echo $tipo_receta ?>" name="tipo_receta" id="tipo_receta" class="form-control" style="text-align:left">
                        </div>
                        <div class="col-md-2 col-md-offset-3">
                          <label>FEC. INGRESO</label>
                          <input type="text" class="form-control datepicker" value="<?php echo date("d/m/Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                          <label>HORA DE INGRESO &nbsp;</label>
                          <input type="time" name="hora" id="hora" value="" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                          <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                          <input type="text" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud" onkeypress="return esEntero(event);" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important" maxlength="12" pattern="[0-9]*">
                        </div>
                      {{--<div class="col-md-3">
                          <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                          <div class="input-group">
                            <input type="text" class="form-control"  id="cod_siteds_susalud" name="cod_siteds_susalud"  onkeypress="return esEntero(event);"
                            style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*" readonly />
                            <span class="input-group-btn">
                              <button type="button" id="btn_generate_siteds" onclick="generarCodigoSiteds();"
                                class="btn btn-sm btn-info" style="margin-top: -1px">Generar</button>
                            </span>
                          </div>
                        </div>--}}
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs" id="myTabs">
                        <li class="active"><a href="#tab0DatosPaciente" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i>
                              INFORMACIÓN DEL PACIENTE</b></a></li>
                        <!-- <li><a href="#tab0DatosPacienteAdicional" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-file-text-o" aria-hidden="true"></i>
                              INFORMACIÓN ADICIONAL</b></a></li> -->
                        <!-- <li><a href="#tab1DatosPacienteAdicional" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-thumb-tack" aria-hidden="true"></i>
                              DIRECCIONES</b></a></li> -->
                        <li id="tab1DatosPacienteTitularLI" name="tab1DatosPacienteTitularLI">
                          <a href="#tab1DatosPacienteTitular" data-toggle="tab" style="font-size:12px" aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i> DATOS DE TITULAR</b></a>
                        </li>
                      </ul>
                    </div>
                    <div class="panel-body" style="">
                      <div class="tab-content">

                        <div class="tab-pane fade active in" id="tab0DatosPaciente">
                          <fieldset>
                            <div id="collapseprestacionespaciente">

                              <div class="row">
                                <div class="col-lg-10 col-md-9 col-sm-12">
                                  @if($id_emergencia == "")
                                  <fieldset>
                                    <div class="form-group">
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipodoc_beneficiario_bus">TIPO DOCUMENTO</label>
                                        <select class="form-control input-sm" id="tipodoc_beneficiario_bus" name="tipodoc_beneficiario_bus" style="width:100%">
                                          <option value="1">DNI</option>
                                          <option value="2">CARNET DE EXTRANJERIA</option>
                                          <option value="3">PASAPORTE</option>
                                          <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                        </select>
                                      </div>

                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label>N° DOCUMENTO</label>
                                        <div class="input-group">
                                          <input id="ndoc_beneficiario_bus" name="ndoc_beneficiario_bus" type="text" autocomplete="off" tabindex="1" class="form-control input-sm text-uppercase" />
                                          <span class="input-group-btn">
                                            <button type="button" id="btnBuscarDniPaciente" name="btnBuscarDniPaciente" class="btn btn-info"
                                              style="height:28px !important;font-size:12px;" tabindex="2">Buscar</button>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                  @endif
                                  <fieldset>
                                    <div class="row">
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label>TIPO DOCUMENTO</label>
                                        <input type="text" id="tipo_doc_ident" class="form-control" readonly="">
                                      </div>

                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label>NRO DOCUMENTO</label>
                                        <input type="text" name="nro_doc_ident" id="nro_doc_ident" class="form-control" maxlength="8" onkeypress="return esEntero(event);" readonly="">
                                        <input type="hidden" name="tipo_registro" id="tipo_registro">
                                        <input type="hidden" name="id_asegurado" id="id_asegurado">
                                        <input type="hidden" value="DNI" name="tipdoc_beneficiario" id="tipdoc_beneficiario">
                                        <input type="hidden" id="dni_beneficiario" name="dni_beneficiario" type="text" class="form-control" />
                                        <input type="hidden" value="1" name="id_tipo_doc_ident" id="id_tipo_doc_ident" class="form-control">
                                        <input type="hidden" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                                        <input type="hidden" name="cip_beneficiario" id="cip_beneficiario" class="form-control">
                                      </div>

                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label>NOMBRES</label>
                                        <input type="text" name="nombre_beneficiario" id="nombre_beneficiario" class="form-control text-uppercase" readonly="">
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label>AP.PATERNO</label>
                                        <input type="hidden" class="form-control" value="" name="beneficiario" id="beneficiario" style="text-align:left">
                                        <input type="text" name="paterno_beneficiario" id="paterno_beneficiario" class="form-control text-uppercase" readonly="">
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label>AP.MATERNO</label>
                                        <input type="text" name="materno_beneficiario" id="materno_beneficiario"
                                          class="form-control text-uppercase" readonly="">
                                      </div>
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label>F.NACIMIENTO &nbsp;</label>
                                        <input type="text" name="fecha_nac" id="fecha_nac" class="form-control"
                                          readonly="">
                                        <input type="hidden" name="fecha_nacimiento_beneficiario"
                                          id="fecha_nacimiento_beneficiario" class="form-control" readonly="">
                                      </div>
                                    <!-- </div>
                                  </fieldset>
                                  
                                  
                                  <fieldset>
                                    <div class="form-group"> -->
                                      
                                      
                                      <div class="col-lg-1 col-md-4 col-sm-12">
                                        <label>SEXO</label>
                                        <input type="text" name="sexo" id="sexo" class="form-control text-uppercase"
                                          readonly="">
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label>EDAD</label>
                                        <input type="text" name="edad_detallada" id="edad_detallada"
                                          class="form-control text-uppercase" readonly="">
                                        <input type="hidden" name="edad" id="edad" class="form-control text-uppercase"
                                          readonly="">
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label>PARENTESCO</label>
                                        <input type="text" name="parentesco_paciente" id="parentesco_paciente"
                                          class="form-control text-uppercase" readonly="">
                                      </div>

                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label>N° HISTORIA CLÍNICA</label>
                                        <input type="text" class="form-control" value="" id="nro_historia"
                                          name="nro_historia" style="text-transform:uppercase;" autocomplete="off"
                                          tabindex="3" onkeyup="javascript:this.value = this.value.toUpperCase();" readonly="">
                                      </div>
                                        <input type="hidden" class="form-control" value="{{$hora_alta ? substr($hora_alta, 0, 5) : ''}}" id="hora_alta_medica"
                                          name="hora_alta_medica" style="text-transform:uppercase;" autocomplete="off"
                                        tabindex="3" onkeyup="javascript:this.value = this.value.toUpperCase();" readonly="">

                                        <input type="hidden" class="form-control" value="{{$fecha_alta ? date('d/m/Y', strtotime($fecha_alta)) : ''}}" id="fecha_alta_medica"
                                            name="fecha_alta_medica" style="text-transform:uppercase;" autocomplete="off">

                                    <!-- </div>
                                  </fieldset>
                                  
                                  <fieldset>
                                    <div class="row"> -->
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">ETNIA</label>
                                        <select class="form-control input-sm select2" name="id_etnia" id="id_etnia" style="width: 100%">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($razas as $row)
                                          <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">TIPO DE SANGRE</label>
                                        <select class="form-control input-sm" name="id_tipo_sangre" id="id_tipo_sangre" style="width: 100%">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($tipo_sangres as $row)
                                          <option value="{{ $row->id }}">{{ $row->descripcion }}</option>
                                          @endforeach
                                        </select>
                                        <input type="hidden" name="tipo_sangre" id="tipo_sangre"
                                          class="form-control text-uppercase" readonly="">
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">ESTADO CIVIL</label>
                                        <select class="form-control input-sm select2" name="id_estado_civil" id="id_estado_civil" style="width: 100%">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($estado_civiles as $row)
                                          <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                          @endforeach
                                        </select>
                                        <input type="hidden" name="id_estado_civil_tomando_dato" id="id_estado_civil_tomando_dato" readonly="">
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">RELIGIÓN</label>
                                        <select class="form-control input-sm select2" name="id_religion" id="id_religion" style="width: 100%">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($religiones as $row)
                                          <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    <!-- </div>
                                  </fieldset>
                                  <fieldset>
                                    <div class="form-group"> -->
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">GRADO DE INSTRUCCIÓN</label>
                                        <select class="form-control input-sm select2" name="id_grado_instruccion" id="id_grado_instruccion" style="width: 100%">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($grado_instrucciones as $row)
                                          <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">OCUPACIÓN</label>
                                        <select class="form-control input-sm select2" name="id_ocupacion" id="id_ocupacion" style="width: 100%">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($ocupaciones as $row)
                                          <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12" style="text-align: center;">
                                  <input type="hidden" name="foto_paciente" id="foto_paciente" readonly="">
                                  <img id="foto_imagen_paciente" src="" style="width: 60%;">
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-md-12">
                                  <label><i class="fa fa-map-marker" aria-hidden="true"></i> NACIMIENTO</label>
                                  <hr>
                                  <fieldset>
                                    <div class="form-group">
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label for="tipo">DEPARTAMENTO</label>
                                        <select class="form-control input-sm select2" name="id_departamento_nacimiento" id="id_departamento_nacimiento" style="width: 100%" onchange="getProvinciaNacimiento();">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($departamentos as $row)
                                          <option value="{{ $row->id_departamento }}">{{ $row->descripcion }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label for="tipo">PROVINCIA</label>
                                        <select class="form-control input-sm select2" name="id_provincia_nacimiento" id="id_provincia_nacimiento" style="width: 100%" onchange="getDistritoNacimiento();">
                                        </select>
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">DISTRITO</label>
                                        <select class="form-control input-sm select2" name="id_distrito_nacimiento" id="id_distrito_nacimiento" style="width: 100%">
                                        </select>
                                      </div>
                                      <div class="col-lg-5 col-md-7 col-sm-12">
                                        <label>DIRECCIÓN</label>
                                        <input type="text" name="direccion_domicilio" id="direccion_nacimiento" class="form-control text-uppercase">
                                      </div>
                                    </div>
                                  </fieldset>

                                  <label><i class="fa fa-map-marker" aria-hidden="true"></i> PROCEDENCIA</label>
                                  <hr>
                                  <fieldset>
                                    <div class="form-group">
                                      <!-- <div class="col-md-12">
                                      <label for=""><i class="fa fa-map-marker" aria-hidden="true"></i> PROCEDENCIA</label>
                                      </div> -->
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label for="tipo">DEPARTAMENTO</label>
                                        <select class="form-control input-sm select2" name="id_departamento_procedencia" id="id_departamento_procedencia" style="width: 100%" onchange="getProvinciaProcedencia();">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($departamentos as $row)
                                          <option value="{{ $row->id_departamento }}">{{ $row->descripcion }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label for="tipo">PROVINCIA</label>
                                        <select class="form-control input-sm select2" name="id_provincia_procedencia" id="id_provincia_procedencia" style="width: 100%" onchange="getDistritoProcedencia();">
                                          <!-- <option value="0" selected>--SELECCIONAR--</option> -->
                                        </select>
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">DISTRITO</label>
                                        <select class="form-control input-sm select2" name="id_distrito_procedencia" id="id_distrito_procedencia" style="width: 100%">
                                          <!-- <option value="0" selected>--SELECCIONAR--</option> -->
                                        </select>
                                      </div>
                                      <div class="col-lg-5 col-md-7 col-sm-12">
                                        <label>DIRECCIÓN</label>
                                        <input type="text" name="direccion_procedencia" id="direccion_procedencia" class="form-control text-uppercase" >
                                      </div>
                                    </div>
                                  </fieldset>
                                  
                                  <label><i class="fa fa-map-marker" aria-hidden="true"></i> DOMICILIO</label>
                                  <hr>
                                  <fieldset>
                                    <div class="form-group">
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label for="tipo">DEPARTAMENTO</label>
                                        <select class="form-control input-sm select2" name="id_departamento_domicilio" id="id_departamento_domicilio" style="width: 100%" onchange="getProvinciaDomicilio();">
                                          <option value="0" selected>--SELECCIONAR--</option>
                                          @foreach ($departamentos as $row)
                                          <option value="{{ $row->id_departamento }}">{{ $row->descripcion }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-2 col-md-4 col-sm-12">
                                        <label for="tipo">PROVINCIA</label>
                                        <select class="form-control input-sm select2" name="id_provincia_domicilio" id="id_provincia_domicilio" style="width: 100%" onchange="getDistritoDomicilio();">
                                          <!-- <option value="0" selected>--SELECCIONAR--</option> -->
                                        </select>
                                      </div>
                                      <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label for="tipo">DISTRITO</label>
                                        <select class="form-control input-sm select2" name="id_distrito_domicilio" id="id_distrito_domicilio" style="width: 100%">
                                          <!-- <option value="0" selected>--SELECCIONAR--</option> -->
                                        </select>
                                      </div>
                                      <div class="col-lg-5 col-md-7 col-sm-12">
                                        <label>DIRECCIÓN</label>
                                        <input type="text" name="direccion_domicilio" id="direccion_domicilio" class="form-control text-uppercase">
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <fieldset id="panel_apoderado" style="display:none;">
                                    <hr>
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12 col-sm-12">
                                        <!-- <div class="form-group" style="margin-bottom: 5px;"> -->
                                          <fieldset>
                                            <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                              <p><b style="font-size: 16px;">DATOS DEL PADRE, MADRE, TUTOR O APODERADO DEL MENOR</b></p>
                                            </div>
                                            </div>
                                            <!-- <div class="col-lg-2 col-md-6 col-sm-12" style="text-align: right" id="copiar_paciente_a_acompaniante" name="copiar_paciente_a_acompaniante">
                                              <label style="cursor: pointer; color: red;"> COPIAR RESPONSABLE </label> <br>
                                              <input type="checkbox" id="copiar_datos_paciente_a_acompaniante" name="copiar_datos_paciente_a_acompaniante" style="margin-left: 10px;cursor: pointer;">
                                            </div> -->
                                          </fieldset>
                                          <fieldset>
                                            <div class="row">
                                              <div class="col-lg-1 col-md-3 col-sm-12">
                                                <label for="tipo">TIPO</label>
                                                <select class="form-control input-sm" id="id_tipo_apoderado" name="id_tipo_apoderado" style="width: 100%" onchange="eventTipoApoderado();">
                                                @foreach ($tipo_parentesco as $row)
                                                  <option value="{{$row->id}}">{{$row->descripcion}}</option>
                                                @endforeach
                                                </select>
                                              </div>

                                            <div id="seccion_principal_apoderado">
                                              <div class="col-lg-2 col-md-3 col-sm-12">
                                                <label for="tipo">TIPO DOC.</label>
                                                <select class="form-control input-sm" id="tipo_doc_apoderado_busqueda" name="tipo_doc_apoderado_busqueda" style="width: 100%">
                                                  <option value="1">DNI</option>
                                                  <option value="2">CARNET DE EXTRANJERIA</option>
                                                  <option value="3">PASAPORTE</option>
                                                  <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                                </select>
                                              </div>

                                              <div class="col-lg-2 col-md-3 col-sm-12">
                                                <label>NRO DE DOCUMENTO</label>
                                                <div class="input-group">
                                                  <input id="nro_doc_apoderado_busqueda" name="nro_doc_apoderado_busqueda" type="text" autocomplete="off" class="form-control input-sm text-uppercase" />
                                                  <span class="input-group-btn">
                                                    <button type="button" id="btn_buscar_apoderado" class="btn btn-info" style="height:28px !important;">Buscar</button>
                                                  </span>
                                                </div>
                                              </div>

                                              <div class="col-lg-2 col-md-3 col-sm-12">
                                                <label>NRO DOCUMENTO</label>
                                                <input type="text" class="form-control text-uppercase" id="nro_doc_apoderado" name="nro_doc_apoderado" readonly="">
                                                <input type="hidden" class="form-control text-uppercase" id="tipo_doc_apoderado" name="tipo_doc_apoderado" readonly="">
                                              </div>

                                              <div class="col-lg-4 col-md-6 col-sm-12">
                                                <label>NOMBRES</label>
                                                <input type="text" class="form-control text-uppercase" id="nombre_apoderado" name="nombre_apoderado" readonly="">
                                              </div>

                                              <div class="col-lg-1 col-md-3 col-sm-12">
                                                <label>TELÉFONO</label>
                                                <input type="text" class="form-control" style="text-transform:uppercase;" id="telefono_apoderado" name="telefono_apoderado" maxlength="9" onkeyup="javascript:this.value = this.value.toUpperCase();" autocomplete="off" tabindex="8">
                                              </div>
                                            </div>
                                            </div>
                                          </fieldset>
                                        <!-- </div> -->
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <fieldset id="panel_acompaniante">
                                    <br>
                                    <hr>
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group" style="margin-bottom: 5px;">
                                          <fieldset>
                                            <div class="col-lg-10 col-md-6 col-sm-12">
                                              <p><b style="font-size: 16px;">DATOS DEL ACOMPAÑANTE</b></p>
                                            </div>
                                            <!-- <div class="col-lg-2 col-md-6 col-sm-12" style="text-align: right" id="copiar_paciente_a_acompaniante" name="copiar_paciente_a_acompaniante">
                                              <label style="cursor: pointer; color: red;"> COPIAR RESPONSABLE </label> <br>
                                              <input type="checkbox" id="copiar_datos_paciente_a_acompaniante" name="copiar_datos_paciente_a_acompaniante" style="margin-left: 10px;cursor: pointer;">
                                            </div> -->
                                          </fieldset>
                                          <fieldset>
                                            <div class="col-lg-1 col-md-3 col-sm-12">
                                              <label for="tipo">TIPO</label>
                                              <select class="form-control input-sm" id="id_tipo_acompaniante" name="id_tipo_acompaniante" style="width: 100%" onchange="eventTipoAcompaniante();">
                                                @foreach ($tipo_parentesco as $row)
                                                <option value="{{$row->id}}">{{$row->descripcion}}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            
                                            <div id="seccion_principal_acompaniante">
                                              <div class="col-lg-2 col-md-3 col-sm-12">
                                                <label for="tipo">TIPO DOC.</label>
                                                <select class="form-control input-sm" id="tipo_doc_acompaniante_busqueda" name="tipo_doc_acompaniante_busqueda" style="width: 100%">
                                                  <option value="1">DNI</option>
                                                  <option value="2">CARNET DE EXTRANJERIA</option>
                                                  <option value="3">PASAPORTE</option>
                                                  <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                                </select>
                                              </div>

                                              <div class="col-lg-2 col-md-3 col-sm-12">
                                                <label>NRO DE DOCUMENTO</label>
                                                <div class="input-group">
                                                  <input id="nro_doc_acompaniante_busqueda" name="nro_doc_acompaniante_busqueda" type="text" autocomplete="off" class="form-control input-sm text-uppercase" />
                                                  <span class="input-group-btn" style="height:28px!important;">
                                                    <button type="button" id="btn_buscar_acompaniante" class="btn btn-info" style="height:32px!important;line-height:10px;margin-top: -2px;">Buscar</button>
                                                  </span>
                                                </div>
                                              </div>

                                              <div class="col-lg-2 col-md-3 col-sm-12">
                                                <label>NRO DOCUMENTO</label>
                                                <input type="text" class="form-control text-uppercase" id="nro_doc_acompaniante" name="nro_doc_acompaniante" readonly="">
                                                <input type="hidden" class="form-control text-uppercase" id="tipo_doc_acompaniante" name="tipo_doc_acompaniante" readonly="">
                                              </div>

                                              <div class="col-lg-4 col-md-6 col-sm-12">
                                                <label>NOMBRES</label>
                                                <input type="text" class="form-control text-uppercase" id="nombre_acompaniante" name="nombre_acompaniante" readonly="">
                                              </div>

                                              <div class="col-lg-1 col-md-3 col-sm-12">
                                                <label>TELÉFONO</label>
                                                <input type="text" class="form-control" style="text-transform:uppercase;" id="telefono_acompaniante" name="telefono_acompaniante" maxlength="9" onkeyup="javascript:this.value = this.value.toUpperCase();" autocomplete="off" tabindex="8">
                                              </div>
                                            </div>
                                          </fieldset>
                                        </div>

                                      </div>
                                    </div>
                                  </fieldset>
                                </div>

                                
                              </div>
                            </div>
                          </fieldset>
                        </div>


                        <div class="tab-pane fade" id="tab1DatosPacienteTitular" name="tab1DatosPacienteTitular">
                          <fieldset>
                            <div class="form-group">
                              <div class="col-lg-3 col-md-6 col-sm-12">
                                <label>TITULAR</label><br>
                                <b id="dni_titular" name="dni_titular" style="font-weight: 400;"></b>
                                <b id="nombre_titular" name="nombre_titular" style="font-weight: 400;"></b>
                              </div>

                              <div class="col-lg-3 col-md-1 col-sm-12">
                                <label>GRADO</label>
                                <p id="grado_titular" name="grado_titular"></p>
                              </div>

                              <div class="col-lg-2 col-md-1 col-sm-12">
                                <label>SITUACIÓN</label>
                                <p id="situacion_titular" name="situacion_titular"></p>
                              </div>

                              <div class="col-lg-1 col-md-1 col-sm-12">
                                <label>SEXO</label>
                                <p id="sexo_titular" name="sexo_titular"></p>
                              </div>

                              <div class="col-lg-3 col-md-6 col-sm-12">
                                <label>UNIDAD PNP</label>
                                <p id="unidad_titular" name="unidad_titular"></p>
                              </div>
                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0DatosMedico" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-user-md" aria-hidden="true"></i>
                              INFORMACIÓN DEL PROFESIONAL QUE ORDENA LA HOSPITALIZACIÓN</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px !important;">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0DatosMedico">
                          @if ($id_emergencia == "")
                          <fieldset>
                            <div class="form-group">
                              <div class="col-md-6 col-sm-12">
                                <label>BUSCAR PROFESIONAL</label>
                                <select name="campo_busqueda_medico" id="campo_busqueda_medico" class="form-control" onchange="getProfesional()">
                                  <option value="0">--SELECCIONAR--</option>
                                  @foreach($profesionales as $r)
                                    <option value="{{$r->id}}">CMP: {{$r->colegiatura}} - RNE: {{$r->rne}} - {{$r->paterno}} {{$r->materno}} {{$r->nombre}}</option>
                                  @endforeach
                                </select>
                                <!-- <input type="text" name="campo_busqueda_medico" id="campo_busqueda_medico"
                                  class="form-control input-sm" tabindex="9"> -->
                              </div>
                            </div>
                          </fieldset>
                          @endif
                          <fieldset>
                            <!-- <div class="panel-body" id="collapseprestacionespaciente"> -->
                              <div class="form-group">

                                <div class="col-lg-2 col-md-3 col-sm-12">
                                  <label>N° DOCUMENTO</label>
                                  <input type="hidden" name="nro_doc_medico" id="nro_doc_medico" class="form-control"
                                    readonly>
                                  <input type="text" name="nro_doc_medico_detallado" id="nro_doc_medico_detallado"
                                    class="form-control" readonly value="">
                                  <input type="hidden" name="id_campo_busqueda_medico" id="id_campo_busqueda_medico" class="form-control input-sm">
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-12">
                                  <label>PROFESIONAL MÉDICO</label>
                                  <input type="text" name="profesional_medico" id="profesional_medico"
                                    class="form-control" readonly>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                  <label>PROFESION &nbsp;</label>
                                  <input type="text" name="especialidad_medico" id="especialidad_medico"
                                    class="form-control text-uppercase" readonly="">
                                </div>
                                <div class="col-lg-2 col-md-1 col-sm-12">
                                  <label>CMP &nbsp;</label>
                                  <input type="text" name="cmp_medico" id="cmp_medico"
                                    class="form-control text-uppercase" readonly="">
                                </div>
                                <div class="col-lg-2 col-md-1 col-sm-12">
                                  <label>RNE &nbsp;</label>
                                  <input type="text" name="rne_medico" id="rne_medico"
                                    class="form-control text-uppercase" readonly="">
                                </div>
                              </div>
                            <!-- </div> -->
                          </fieldset>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>


              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0DatosAdmision" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-file-text-o" aria-hidden="true"></i>
                              DATOS DE INGRESO & DIAGNÓSTICOS</b></a></li>
                      </ul>
                    </div>

                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0DatosAdmision">
                          <fieldset>
                            <div id="collapseprestacionespaciente" style="padding-bottom: 0px !important;">
                              <div class="form-group" style="margin-bottom: auto;">


                                
                                <div class="col-lg-3 col-md-4 col-sm-12">
                                  <label for="tipo">TIPO DE ORIGEN</label>
                                  @if ($id_emergencia == "")
                                  <select name="origen" id="origen" style="width:100%">
                                    <option value="">--SELECCIONAR--</option>
                                    @foreach($tipo_origenes as $r)
                                      <option value="{{$r->id}}">{{$r->nombre}}</option>
                                    @endforeach
                                  </select>
                                  @else
                                  <input type="hidden" name="origen" id="origen" class="form-control text-uppercase"
                                    readonly="" value="2">
                                  <input type="text" name="origen_descripcion" id="origen_descripcion"
                                    value="EMERGENCIA" class="form-control text-uppercase" readonly="">
                                  @endif
                                </div>


                                <!-- <div class="col-lg-1 col-md-2 col-sm-12" style="margin-top: -8px;"><br><br>
                                  <label style="cursor: pointer;color: red;">REFERIDO</label>
                                  <input type="checkbox" id="habiliar_referencia" name="habiliar_referencia"
                                    tabindex="12" style="margin-left: 15px;cursor: pointer;margin-top: 5px;">
                                  <input type="hidden" name="es_referido" id="es_referido"
                                    class="form-control text-uppercase">
                                </div> -->

                              </div>
                            </div>
                          </fieldset>




                          <!-- <fieldset>
                            <div class="form-group" style="margin-bottom: auto; padding-top: 10px;">

                              <div id="seccion_de_referencia" name='seccion_de_referencia'>
                                <div class="col-lg-1 col-md-2 col-sm-12 text-center">
                                  <label style="cursor: pointer;">NO PNP</label> <br>
                                  <input type="checkbox" id="habiliar_no_pnp" name="habiliar_no_pnp"
                                    style="margin-left: 12px;cursor: pointer;" tabindex="13">
                                  <input type="hidden" name="es_pnp" id="es_pnp" class="form-control text-uppercase"
                                    value="1">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                  <label for="topico">IPRESS DE ORIGEN</label>
                                  <input type="text" name="descripcion_ipress_origen_referencia" tabindex="14"
                                    id="descripcion_ipress_origen_referencia" class="form-control text-uppercase"
                                    onkeyup="javascript:this.value = this.value.toUpperCase();" autocomplete="off">
                                  <input type="hidden" name="id_ipress_origen_referencia"
                                    id="id_ipress_origen_referencia" class="form-control input-sm">
                                </div>

                                <div class="col-lg-2 col-md-3 col-sm-12">
                                  <label>NUMERO</label>
                                  <input type="text" name="numero_de_referencia" id="numero_de_referencia"
                                    class="form-control text-uppercase" tabindex="15"
                                    onkeyup="javascript:this.value = this.value.toUpperCase();" autocomplete="off">
                                </div>

                                <div class="col-lg-2 col-md-3 col-sm-12">
                                  <label>FECHA</label>
                                  <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                    name="fecha_de_referencia" id="fecha_de_referencia" autocomplete="off" tabindex="16"
                                    placeholder="DD/MM/AAAA">
                                </div>
                              </div>
                            </div>
                          </fieldset> -->



                          <fieldset>
                            <div class="form-group" style="margin-bottom: auto;"><br>
                              <div class="col-md-3">
                                <label>UPSS A CARGO</label>
                                @if ($id_emergencia == "")
                                  <select name="id_upss_encargado" id="id_upss_encargado" style="width:100%" onchange="getSala()">
                                    <option value="">--SELECCIONAR--</option>
                                    @foreach($upsses as $r)
                                      <option value="{{$r->id_upss}}">{{$r->codigo_upss}} - {{$r->descripcion_upss}}</option>
                                    @endforeach
                                  </select>
                                @else
                                <input type="hidden" class="form-control" id="id_upss_encargado" name="id_upss_encargado">
                                <input type="text" class="form-control" id="descripcion_upss_encargado" name="descripcion_upss_encargado" readonly="">
                                @endif
                              </div>
                              <div class="col-md-3">
                                <label>SERVICIO A CARGO</label>
                                @if ($id_emergencia == "")
                                  <select name="id_servicio_encargado" id="id_servicio_encargado" style="width:100%">
                                  </select>
                                @else
                                <input type="hidden" class="form-control" id="id_servicio_encargado" name="id_servicio_encargado">
                                <input type="text" class="form-control" id="nombre_servicio_encargado" name="nombre_servicio_encargado" readonly="">
                                @endif
                              </div>
                              <!-- ***************************************************************** -->
                              <div class="col-md-3">
                                <label>SERVICIO O SALA</label>
                                <select name="id_servicio_destino" id="id_servicio_destino" style="width:100%" onchange="getCama2()">
                                  <option value="">--SELECCIONAR--</option>
                                  @foreach ($pabellones_servicios_cuartos as $row)
                                  <option value="{{ $row->id_cuarto }}">{{ $row->pabellon }} <strong>/</strong> {{ $row->servicio_sala }} <b>/</b> {{ $row->cuarto }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label>CAMA</label>
                                <select id="id_cama" name="id_cama" class="form-control" style="width: 100%" tabindex="19">
                                </select>
                              </div>
                            </div>
                          </fieldset>
                          <label for="">&nbsp;</label>
                          <hr>
                          <fieldset>
                            @if ($id_emergencia == "")
                            <table>
                              <tr>
                                <td>
                                  <button type="button" id="addDiagnostico" class="btn btn-success btn-sm" tabindex="20"
                                    href="javascript:void(0)">
                                    <b><i class="fa fa-plus" aria-hidden="true"></i></b> AGREGAR DIAGNÓSTICOS</button>
                                </td>
                              </tr>
                            </table>
                            <br />
                            @endif
                            <table class="display" id="tblDiagnostico" cellspacing="0" width="100%"
                              style="margin-top:5px;">
                              <thead>
                                <tr>
                                  <th width="100%">DIAGNÓSTICOS</th>
                                </tr>
                              </thead>
                              <tfoot>
                              </tfoot>
                              <tbody>
                              </tbody>
                            </table>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>


              <div class="form-group">
                <div class="col-md-6">
                  <a href="javascript:void(0)" id="cancelar_registro_admision" name="cancelar_registro_admision"
                    class="form-control btn btn-danger btn-sm" style="text-align:center!important">CANCELAR</a>
                </div>
                <div class="col-md-6">
                  <a class="form-control btn btn-success btn-sm" onclick="guardarNueva_admision_hospitalizacion()"
                    style="text-align:center!important">GUARDAR</a>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection