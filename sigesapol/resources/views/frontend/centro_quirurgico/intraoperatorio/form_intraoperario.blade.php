<!-- Cargar jQuery UI 1.12.1 CSS y JavaScript -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="{{asset('sweetalert/sweetalert.min.js')}}"></script> 
<style>
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

    /*************Styles de sweetAlert2*************/
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

    .descripcion-alert span {
        font-weight: 700;
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

    .titulo-padding {
      background-color: #3c763d;
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

    /*********************************************/
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

    .diagnostico-error {
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

</style>
<style>
  .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 10000000;
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
  .ui-state-hover, .ui-state-active, .ui-state-focus {
      text-decoration: none;
      color: #FFFFFF !important;
      background: #1d71b8 !important;
      cursor: pointer;
  }
</style>

<script>
  document.getElementById('cirugia_inicio').addEventListener('change', calcularDuracion);
  document.getElementById('cirugia_termino').addEventListener('change', calcularDuracion);

  function calcularDuracion() {
      const inicio = document.getElementById('cirugia_inicio').value;
      const termino = document.getElementById('cirugia_termino').value;

      if (inicio && termino) {
          // Convertir ambos tiempos a milisegundos desde la medianoche
          const inicioMilis = convertirATiempoEnMilisegundos(inicio);
          const terminoMilis = convertirATiempoEnMilisegundos(termino);

          // Calcular la diferencia en milisegundos
          const diferenciaMilis = terminoMilis - inicioMilis;

          if (diferenciaMilis >= 0) {
              // Convertir la diferencia en formato HH:MM
              const duracion = convertirMilisegundosATiempo(diferenciaMilis);
              document.getElementById('tipo_real').value = duracion;
              document.getElementById('tipo_real2').value = duracion;
          } else {
              alert("El término de la cirugía debe ser después del inicio.");
              document.getElementById('tipo_real').value = "";
          }
      }
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
      
      // Asegurar que el formato sea HH:MM
      return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
  }
  $(document).ready(function() {
    $('#modal-intraoperatorio').on('hidden.bs.modal', function () {
      // Solo actúa si hay contenido ya cargado en una variable global
      if (window.resultDetalleIntraop) {
        $("#div-modal-detalle-intraoperatorio").html(window.resultDetalleIntraop);
        $('#modal-detalle-intraoperatorio').modal({
          backdrop: 'static', 
          keyboard: false
        }).modal('show');

        // Limpia la variable
        window.resultDetalleIntraop = null;
      }
    });

    let cirujanoId; // Declarar la variable aquí
    var procedimientosSeleccionados =[];
    var listIdDiagnostico =[];
    var listIdDiagnosticoObjetos =[];
    setTodayDate();
    setCurrentTime();

    let codigo_siteds = $('#cod_siteds_susalud').val();
    if(!codigo_siteds){
      console.log('generarCodigoSiteds...');
      // generarCodigoSiteds();
    }
    getDiagnosticosByPrestacion();
    
    $('#addCirujanoIntraoperatorio').on('click',function(){
      AddCirujanoIntraoperatorio();
    });
    $('#addAnestesiologo').on('click',function(){
      AddAnestesiologo();
    });
    $('#addEnfermeriaInstrumentista').on('click',function(){
      AddEnfermeriaInstrumentista();
    });
    $('#addDiagnostico').on('click',function(){
      AddDiagnostico();
    });
    $('#addProcedimiento').on('click',function(){
      AddProcedimiento();
      //var id_prestacion = <?php //echo $asegurado['id_prestacion']; ?>;
      agregarDiagnosticosAprocedimientos();
    });
    $('#addBalanceFluidos').on('click',function(){
      AddBalanceFluidos();
    });

    $('#tblCirujano tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });
    $('#tblAnestesiologo tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });
    $('#tblEnfermeriaInstrumentista tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });
    $('#tblBalanceFluidos tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });
    $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      // Selecciona la fila completa
      var fila = obj.closest('tr');
      // Obtiene la posición de la fila
      var filaPosition = fila.index();
      // Elimina la fila
      obj.parent().parent().remove();
    });
   
    $('#btnGuardarIntraOperatorio').on("click", function (e) {
      e.preventDefault(); 
      // Inicia el modal de SweetAlert2 sin opacidad en el fondo
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
              $('#formIntraoperario').submit(); // Envía el formulario
          }
      });
    });

    // Manejo del envío del formulario
    $('#formIntraoperario').on('submit', function(e) {
        e.preventDefault(); // Detiene el envío normal del formulario
        console.log('Formulario enviado con AJAX');

        var formData = $(this).serialize(); // Serializa los datos del formulario
        $.ajax({
            url: $(this).attr('action'), // La URL a la que enviar los datos
            method: 'POST', // Método HTTP
            data: formData, // Los datos del formulario serializados
            success: function(response) {
                Swal.close(); // Cierra el loading
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').hide(); // Asegúrate de ocultar todos los mensajes
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Los datos se han guardado correctamente.',
                        timer: 3000, // Tiempo en milisegundos antes de cerrar automáticamente (3 segundos)
                        showConfirmButton: false // Elimina el botón "OK"
                    }).then(() => {
                        $.ajax({
                          url: "/detalle_intraoperario/"+response.id_intraoperatorio,
                          type: "GET",
                          data: {
                            _token: $('input[name="_token"]').val()
                          },
                          success: function(result) { 
                            $('#modal-intraoperatorio').on('hidden.bs.modal', function () {
                                $('#modal-detalle-intraoperatorio').modal({
                                    backdrop: 'static', 
                                    keyboard: false
                                });
                                $("#div-modal-detalle-intraoperatorio").html(result); 
                                $('#modal-detalle-intraoperatorio').modal('show');
                            });
                            $('#modal-intraoperatorio').modal('hide');
                          }
                        });
                    });
                } else {
                  validaCampos(response);
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
                // Manejar el error (ejemplo: mostrar mensaje de error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la solicitud. Inténtalo de nuevo.'
                });
            }
        });
    });

    function validaCampos(response){
      $.each(response.errors, function(field, error) {
       
        // if (field.startsWith('nombre_cirujano')) {
         
        //   $('input[id^="nombre_cirujano"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#nombre_cirujano' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //         //$inputField.next('.invalid-feedback').text(error[0]).show();  // Mostramos el mensaje de error
        //       }
        //     }
        //   });
        // }else if (field.startsWith('tipo_profesional_cirujano')) {
        //   $('input[id^="tipo_profesional_cirujano"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#tipo_profesional_cirujano' + index);  
  
        //       if ($inputField.length) {
        //         console.log('tipo profesionalo 222222');
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else if (field.startsWith('nombre_anestesiologo')) {
        //   $('input[id^="nombre_anestesiologo"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#nombre_anestesiologo' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else if (field.startsWith('tipo_profesional_anestesiologo')) {
        //   $('input[id^="tipo_profesional_anestesiologo"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#tipo_profesional_anestesiologo' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else if (field.startsWith('nombre_enfermeria_instrumentista')) {
        //   $('input[id^="nombre_enfermeria_instrumentista"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#nombre_enfermeria_instrumentista' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else if (field.startsWith('tipo_instrumentista')) {
        //   $('input[id^="tipo_instrumentista"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#tipo_instrumentista' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else if (field.startsWith('nombre_procedimiento')) {
        //   $('input[id^="nombre_procedimiento"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#nombre_procedimiento' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else if (field.startsWith('procedimiento_cantidad')) {
        //   $('input[id^="procedimiento_cantidad"]').each(function() {
        //     console.log('procedimiento_cantidad');
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     let index = match[2]; // Extrae el número como índice
        //     var $inputField = $('#procedimiento_cantidad' + index); // Selecciona el input con ese índice
            
        //     // Verifica que el input exista antes de aplicar la clase y mostrar el error
        //     if ($inputField.length) {
        //         // Opcional: Condiciones de validación antes de agregar la clase is-invalid
        //         let value = $inputField.val();
        //         // Ejemplo: validar que el valor sea un número y esté dentro de un rango (1-99)
        //         if (value === '' || value === 0 || isNaN(value) || parseInt(value) <= 0 || parseInt(value) > 99) {
        //             $inputField.addClass('is-invalid');  // Agrega la clase 'is-invalid' si no cumple la validación
        //         } else {
        //             $inputField.removeClass('is-invalid'); // Remueve la clase si cumple la validación
        //         }
        //     }
        //   });
        // }else if (field.startsWith('miSelect')) {
        //   $('input[id^="miSelect"]').each(function() {
        //     let inputId = $(this).attr('id'); // Obtiene el ID del input
        //     let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
        //     if (match) {
        //       index = match[2]; 
        //       var $inputField = $('#miSelect' + index);  
        //       if ($inputField.length) {
        //         $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
        //       }
        //     }
        //   });
        // }else 
        if (field.startsWith('balance_ingreso')) {
          $('input[id^="balance_ingreso"]').each(function() {
            let inputId = $(this).attr('id'); // Obtiene el ID del input
            let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            if (match) {
              index = match[2]; 
              var $inputField = $('#balance_ingreso' + index);  
              if ($inputField.length) {
                $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
              }
            }
          });
        }else if (field.startsWith('balance_egreso')) {
          $('input[id^="balance_egreso"]').each(function() {
            let inputId = $(this).attr('id'); // Obtiene el ID del input
            let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            if (match) {
              index = match[2]; 
              var $inputField = $('#balance_egreso' + index);  
              if ($inputField.length) {
                $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
              }
            }
          });
        }else if (field.startsWith('tareapeutica')) {
          $('input[id^="tareapeutica"]').each(function() {
            let inputId = $(this).attr('id'); // Obtiene el ID del input
            let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            if (match) {
              index = match[2]; 
              var $inputField = $('#tareapeutica' + index);  
              if ($inputField.length) {
                $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
              }
            }
          });
        }else if (field.startsWith('enfermeriauno')) {
          $('input[id^="enfermeriauno"]').each(function() {
            
            let inputId = $(this).attr('id'); // Obtiene el ID del input
            let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            if (match) {
              index = match[2]; 
              var $inputField = $('#enfermeriauno' + index);  
              if ($inputField.length) {
                $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
              }
            }
          });
        }else if (field.startsWith('enfermeriados')) {
          $('input[id^="enfermeriados"]').each(function() {
            let inputId = $(this).attr('id'); // Obtiene el ID del input
            let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            if (match) {
              index = match[2]; 
              var $inputField = $('#enfermeriados' + index);  
              if ($inputField.length) {
                $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
              }
            }
          });
        }else if (field.startsWith('enfermeriatres')) {
          $('input[id^="enfermeriatres"]').each(function() {
            let inputId = $(this).attr('id'); // Obtiene el ID del input
            let match = inputId.match(/(\D+)(\d+)/); // Captura el texto y el número
            if (match) {
              index = match[2]; 
              var $inputField = $('#enfermeriatres' + index);  
              if ($inputField.length) {
                $inputField.addClass('is-invalid');  // Agregamos la clase 'is-invalid'
              }
            }
          });
        }else if (field.startsWith('diagnostico_estado')) {
            let checkboxesSeleccionados = $('input[name="diagnostico_estado[]"]:checked').length;
            if (checkboxesSeleccionados === 0) {
               $('input[name="diagnostico_estado[]"]').addClass('is-invalid');
            }
        }else if (field.startsWith('nic_estado')) {
            let minimi_un_hijo_por_cada_padre=false;
            $('input[id^="nic_estado"]').each(function() {
              let checkboxName = $(this).attr('name');
              let match = checkboxName.match(/nic_estado\[(\d+)\]/);
              if (match) {
                let padreId = match[1]; 
                let checkboxesHijos = $(`input[name^="nic_estado[${padreId}"]`);
                let hijosChequeados = checkboxesHijos.filter(':checked').length;
                if (hijosChequeados === 0) {
                    checkboxesHijos.addClass('is-invalid');
                    minimi_un_hijo_por_cada_padre=true;
                    console.log(hijosChequeados);
                } else {
                  console.log('no chekeado');
                    checkboxesHijos.removeClass('is-invalid');
                    minimi_un_hijo_por_cada_padre=false;
                }
              } 
            }); 
            if (minimi_un_hijo_por_cada_padre) {
              $('#nicError').html('Debe seleccionar al menos una intervención en fermería por cada sección').show();
            }else{
              $('#nicError').html('').hide();
            }
         }else{
          console.log(field);
          var $inputField = $('#' + field);
          if ($inputField.length) {
              $inputField.addClass('is-invalid');
              $inputField.next('.invalid-feedback').text(error[0]).show();
          }
        }
      });

      //VALIDACIONES PARA OBLIGAR AL MENOS UN REGISTRO
      let cantidadCirujano = 0; 
      let cantidadAnestesiologo = 0; 
      let cantidadInstrumentista = 0;
      let cantidadProcedimiento = 0;
      let cantidadBalanceFluidos = 0;
      let cantidadDiagnosticosEnfermeriaCkekeado  = 0;
      let cantidadNicCkekeado = 0;
      //CIRUJANO
      // $('input[name="nombre_cirujano[]"]').each(function() {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //       cantidadCirujano=1;
      //       $(this).removeClass('is-invalid'); 
      //     }
      // });
      // $('select[name="tipo_profesional_cirujano[]"]').each(function(index) {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //         $(this).removeClass('is-invalid'); 
      //         if ($('input[name="tipo_profesional_cirujano[]"]').eq(index).val() !== '') {
               
      //         }
      //     }
      // });
      // if (cantidadCirujano==0) {
      //   $('#addCirujanoIntraoperatorio').addClass('is-invalid');
      //   $('#addCirujanoIntraoperatorio').next('.invalid-feedback').text('Debe de ingresar por lo menos un cirujano y el tipo').show();
      // }else{
      //   $('#addCirujanoIntraoperatorio').removeClass('is-invalid');
      //   $('#addCirujanoIntraoperatorio').removeClass('invalid-feedback');
      // }
      //FIN CIRUJANO

      //ANESTESIOLOGO
      // $('input[name="nombre_anestesiologo[]"]').each(function() {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //       cantidadAnestesiologo=1; 
      //       $(this).removeClass('is-invalid'); 
      //     }
      // });
      // $('select[name="tipo_profesional_anestesiologo[]"]').each(function(index) {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //         $(this).removeClass('is-invalid'); 
      //         if ($('input[name="tipo_profesional_anestesiologo[]"]').eq(index).val() !== '') {
               
      //         }
      //     }
      // });
      // if (cantidadAnestesiologo==0) {
      //   $('#addAnestesiologo').addClass('is-invalid');
      //   $('#addAnestesiologo').next('.invalid-feedback').text('Debe de ingresar por lo menos un anestesiologo y el tipo').show();
      // }else{
      //   $('#addAnestesiologo').removeClass('is-invalid');
      //   $('#addAnestesiologo').removeClass('invalid-feedback');
      // }
      //FIN ANESTESIOLOGO

      //ENFERMERO INSTRUMENTISTA
      $('input[name="nombre_enfermeria_instrumentista[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
            cantidadInstrumentista=1; 
            $(this).removeClass('is-invalid'); 
          }
      });
      $('select[name="tipo_instrumentista[]"]').each(function(index) {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
              $(this).removeClass('is-invalid'); 
              if ($('input[name="tipo_instrumentista[]"]').eq(index).val() !== '') {
               
              }
          }
      });
      // if (cantidadInstrumentista==0) {
      //   $('#addEnfermeriaInstrumentista').addClass('is-invalid');
      //   $('#addEnfermeriaInstrumentista').next('.invalid-feedback').text('Debe de ingresar por lo menos un anestesiologo y el tipo').show();
      // }else{
      //   $('#addEnfermeriaInstrumentista').removeClass('is-invalid');
      //   $('#addEnfermeriaInstrumentista').removeClass('invalid-feedback');
      // }
      //FIN ENFERMERO INSTRUMENTISTA

      //ENFERMERO PROCEDIMIENTO
      $('input[name="nombre_procedimiento[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
            cantidadProcedimiento=1; 
            $(this).removeClass('is-invalid'); 
          }
      });
      $('input[name="procedimiento_cantidad[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else { 
            $(this).removeClass('is-invalid'); 
          }
      });
      $('select[name="miSelect[]"]').each(function(index) {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
              $(this).removeClass('is-invalid'); 
              if ($('input[name="miSelect[]"]').eq(index).val() !== '') {
               
              }
          }
      });
      if (cantidadProcedimiento==0) {
        $('#addProcedimiento').addClass('is-invalid');
        $('#addProcedimiento').next('.invalid-feedback').text('Debe de ingresar por lo menos un procedimiento').show();
      }else{
        $('#addProcedimiento').removeClass('is-invalid');
        $('#addProcedimiento').removeClass('invalid-feedback');
      }
      //FIN ENFERMERO INSTRUMENTISTA

      //BALANCE DE FLUIDOS
      $('input[name="balance_ingreso[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
            cantidadBalanceFluidos=1; 
            $(this).removeClass('is-invalid'); 
          }
      });
      $('input[name="balance_egreso[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
            cantidadBalanceFluidos=1; 
            $(this).removeClass('is-invalid'); 
          }
      });
      $('input[name="tareapeutica[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else {
            cantidadBalanceFluidos=1; 
            $(this).removeClass('is-invalid'); 
          }
      });
      
      // if (cantidadBalanceFluidos==0) {
      //   $('#addBalanceFluidos').addClass('is-invalid');
      //   $('#addBalanceFluidos').next('.invalid-feedback').text('Debe de ingresar por lo menos un registro en balance de fluidos').show();
      // }else{
      //   $('#addBalanceFluidos').removeClass('is-invalid');
      //   $('#addBalanceFluidos').removeClass('invalid-feedback');
      // }
      //FIN BALANCE DE FLUIDOS

      //RECUENTO DE GASAS
      // $('input[name="enfermeriauno[]"]').each(function() {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //       $(this).removeClass('is-invalid'); 
      //     }
      // });
      // $('input[name="enfermeriados[]"]').each(function() {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //       $(this).removeClass('is-invalid'); 
      //     }
      // });
      // $('input[name="enfermeriatres[]"]').each(function() {
      //     if ($(this).val() === '') {
      //         $(this).addClass('is-invalid'); 
      //     } else {
      //       $(this).removeClass('is-invalid'); 
      //     }
      // });
      //FIN RECUENTO DE GASAS

      //DIAGNOSTICOS DE ENFERMWERIA
      $('input[name="diagnostico_estado[]"]').each(function() {
          if ($(this).is(':checked')) {
            cantidadDiagnosticosEnfermeriaCkekeado++;
          }
      });
      // if (cantidadDiagnosticosEnfermeriaCkekeado==0) {
      //   $('#diagnosticoError').html('Debe seleccionar al menos un diagnóstico de enfermeria').show();
      // }else{
      //   $('#diagnosticoError').html('').hide();
      // }
      //FIN DIAGNOSTICOS DE ENFERMWERIA
      
    }

    function AddCirujanoIntraoperatorio() {
      var newRow = "";
      var ind = $('#tblCirujano tbody tr').length;

      if (ind > 0) {
        $('.id_cirujano').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblCirujano tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow +='<td style="width:60%;">'+
                '<input type="hidden" name="flag_personales_cirujano[]" value="0" />'+
                '<input type="text"   name="nombre_cirujano[]" '+
                'id="nombre_cirujano'+ ind +'" class="form-control" style="height: 28px !important;font-weight: 800;" required />'+
                '<input type="hidden" class="form-control typeahead tt-query id_cirujano" id="id_cirujano' + ind +'" name="id_cirujano[]"  data-id='+ind+'></td>';
      newRow +='<td style="width:20%;"><select id="tipo_profesional_cirujano' + ind +'" name="tipo_profesional_cirujano[]" class="form-control" style="height: 28px !important;font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
      newRow +='<td style="width:15%;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblCirujano tbody').append(newRow);

      var tipo_profesional_interviene = <?php echo json_encode($tipo_profesional_interviene_cirujano); ?>;
      var $select = $('#tipo_profesional_cirujano' + ind +'');
      $.each(tipo_profesional_interviene, function(index, value) {
        $select.append($('<option></option>').val(value.id).text(value.descripcion));
      });

      $("#nombre_cirujano" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          url_consulta= '{{url('profesional_que_interviene/')}}/'+request.term ;  
          $.ajax({
            url: url_consulta,
            dataType: "json",
            success: function(data) {
              let listaSirujanosSeleccionado = $(".id_cirujano").map(function(){
                  return parseInt($(this).val());
              }).get();

              let listaAnestesiologosSelecionado = $(".id_anestesiologo").map(function(){
                  return parseInt($(this).val());
              }).get();

              // Filtro que excluye los elementos que estén en ambas listas
              let filtro = data.filter((element) => {
                  return !listaSirujanosSeleccionado.includes(element.id) && 
                        !listaAnestesiologosSelecionado.includes(element.id);
              });

              // Aplicar el límite de los primeros 10 elementos
              let limitedData = filtro.slice(0, 10);
              response(limitedData);
            }
          });
        },
        select: function(e, ui) {
          var id_relacionado = ui.item.id;
          //var descripcion = ui.item.value; 
          $("#id_cirujano" + ind).val(id_relacionado);
          $("#nombre_cirujano" + ind).prop("readonly", true);
        }
      });
      
    }
    function AddAnestesiologo() {
      var newRow = "";
      var ind = $('#tblAnestesiologo tbody tr').length;

      if (ind > 0) {
        $('.id_anestesiologo').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblAnestesiologo tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow +='<td style="width:60%;">'+
                '<input type="hidden" name="flag_personales_anestesiologo[]" value="1" />'+
                '<input type="text" data-toggle="tooltip" data-placement="top"  name="nombre_anestesiologo[]" '+
                'id="nombre_anestesiologo' +ind + '"  class="form-control" style="height: 28px !important;font-weight: 800;" required/>'+
                '<input type="hidden" class="form-control typeahead tt-query id_anestesiologo" id="id_anestesiologo' + ind +'" name="id_anestesiologo[]"  data-id='+ind+'></td>';
      newRow +='<td style="width:20%;"><select id="tipo_profesional_anestesiologo' + ind +'" name="tipo_profesional_anestesiologo[]" class="form-control" style="height: 28px !important;font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
      newRow +='<td style="width:15%;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblAnestesiologo tbody').append(newRow);
      
      var tipo_profesional_interviene = <?php echo json_encode($tipo_profesional_interviene_anestesiologo); ?>;
      var $select = $('#tipo_profesional_anestesiologo' + ind +'');
      $.each(tipo_profesional_interviene, function(index, value) {
        $select.append($('<option></option>').val(value.id).text(value.descripcion));
      });
    
      $("#nombre_anestesiologo" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          url_consulta= '{{url('profesional_que_interviene/')}}/'+request.term;    
          $.ajax({
            url: url_consulta,
            dataType: "json",
            success: function(data) {
              let listaSirujanosSeleccionado = $(".id_cirujano").map(function(){
                  return parseInt($(this).val());
              }).get();

              let listaAnestesiologosSelecionado = $(".id_anestesiologo").map(function(){
                  return parseInt($(this).val());
              }).get();

              // Filtro que excluye los elementos que estén en ambas listas
              let filtro = data.filter((element) => {
                  return !listaSirujanosSeleccionado.includes(element.id) && 
                        !listaAnestesiologosSelecionado.includes(element.id);
              });

              // Aplicar el límite de los primeros 10 elementos
              let limitedData = filtro.slice(0, 10);
              response(limitedData);
            }
          });
        },
        select: function(e, ui) {
          var id_anestesiologo = ui.item.id;
          $("#id_anestesiologo" + ind).val(id_anestesiologo);
          $("#nombre_anestesiologo" + ind).prop("readonly", true);
        }
      });
      
    }
    function AddEnfermeriaInstrumentista() {
      let id_especialidad=8;//ESPECIALIDAD ENFERMERA
      var newRow = "";
      var ind = $('#tblEnfermeriaInstrumentista tbody tr').length;

      if (ind > 0) {
        $('.id_enfermeria_instrumentista').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblEnfermeriaInstrumentista tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow +='<td style="width:60%;">'+
                '<input type="hidden" name="flag_personales_enfermero[]" value="2" />'+
                '<input type="text" data-toggle="tooltip" data-placement="top"  name="nombre_enfermeria_instrumentista[]"  '+
                'id="nombre_enfermeria_instrumentista' +ind + '"  class="form-control" style="height: 28px !important;font-weight: 800;" required />'+
                '<input type="hidden" class="form-control typeahead tt-query id_enfermeria_instrumentista" id="id_enfermeria_instrumentista' + ind +'" name="id_enfermeria_instrumentista[]"  data-id='+ind+'></td>';
      newRow +='<td style="width:20%;"><select id="tipo_instrumentista' + ind +'" name="tipo_instrumentista[]" class="form-control" style="height: 28px !important;font-weight: 800;"><option value="">Seleccione un tipo</option></select></td>';
      newRow +='<td style="width:15%;"><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblEnfermeriaInstrumentista tbody').append(newRow);

      var tipo_instrumentista = <?php echo json_encode($tipo_instrumentista); ?>;
      var $select = $('#tipo_instrumentista' + ind +'');
      $.each(tipo_instrumentista, function(index, value) {
        $select.append($('<option></option>').val(value.id).text(value.descripcion));
      });
      $("#nombre_enfermeria_instrumentista" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            url_consulta= '{{url('profesional_que_interviene/')}}/'+request.term +'/'+id_especialidad;   
            $.ajax({
            url: url_consulta,
            dataType: "json",
            success: function(data) {
              let listaInstrumentistaSelecionado = $(".id_enfermeria_instrumentista").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaInstrumentistaSelecionado.includes(element.id));
              response(filtro);
            }
          });
        },
        select: function(e, ui) {
          var id_enfermeria_instrumentista = ui.item.id;
          $("#id_enfermeria_instrumentista" + ind).val(id_enfermeria_instrumentista);
          $("#nombre_enfermeria_instrumentista" + ind).prop("readonly", true);
        }
      });
    }
    function AddProcedimiento() {
      var newRow = "";
      var ind = $('#tblProcedimiento tbody tr').length;

      if (ind > 0) {
        $('.id_procedimiento').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblProcedimiento tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow += '<td style="width:60%;"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
        ind + '" class="form-control" style="height: 28px !important;font-weight: 800;" required />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
        '" name="id_procedimiento[]"  data-id='+ind+'></td>';
      newRow +=
        '<td style="width:15%;"><input type="number" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
        ind + '" name="procedimiento_cantidad[]" value="1" required></td>';
      newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
      newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimiento tbody').append(newRow);

      

      const selectElement = document.getElementById('miSelect' + ind);
      let defaultOption = document.createElement('option');
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
     
      var nivel = $('#id_nivel').val();
      var sexo = $('#sexo').val();
      let genero = "";
      if (sexo == 'MASCULINO')
        genero = 1;
      else
        genero = 2;
      $("#nombre_procedimiento" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          console.log(request);
          
          $.ajax({
            url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            dataType: "json",
            success: function(data) {
              let listaProcedimientoSelecionado = $(".id_procedimiento").map(function(){return parseInt($(this).val());}).get();
              let filtro = data.filter((element)=>!listaProcedimientoSelecionado.includes(element.id));
              response(filtro);
            }
          });
        },
        select: function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento" + ind).val(id_procedimiento);
          $("#nombre_procedimiento" + ind).prop("readonly", true);
        }
      }); 
    }
    function AddBalanceFluidos() {
      var newRow = "";
      var ind = $('#tblBalanceFluidos tbody tr').length;

      if (ind > 0) {
        $('.id_balance_ingreso').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblBalanceFluidos tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registros = fila_registros[idval];
      }

      newRow += '<tr>';
        newRow += '<td>'+
                '<input type="text" data-toggle="tooltip" data-placement="top" name="balance_ingreso[]" id="balance_ingreso' +ind + '" class="form-control input-sm" style="text-align:left" />'+
                '<input type="hidden" class="form-control typeahead tt-query id_balance_ingreso" id="id_balance_ingreso' + ind +'" name="id_balance_ingreso[]"  data-id='+ind+'></td>';
        newRow += '<td style="padding-left: 1rem;">'+
                  '<input type="text" data-toggle="tooltip" data-placement="top"  name="balance_egreso[]" id="balance_egreso' +ind + '" class="form-control input-sm" style="text-align:left" /></td>';
        newRow += '<td style="padding-left: 1rem;">'+
          '<input type="text" data-toggle="tooltip" data-placement="top" name="tareapeutica[]"  id="tareapeutica' +ind + '" class="form-control input-sm" style="text-align:left" /></td>';
        newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblBalanceFluidos tbody').append(newRow);
      
    }
    function getDiagnosticosByPrestacion(){
      var lista_diagnosticos = <?php echo json_encode($diagnosticos);?>;
      console.log(lista_diagnosticos);
      
      var cuerpo_DIAGNOSTICOS_PRESTACION = '' ;
          /***************DATOS PARA LA TABLA***********/
            $("#diagnosticos_io").empty();
            $.each(lista_diagnosticos, function (key, value) { 
              cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION+
                  '<tr>'+ 
                    '<td>'+value.codigo+'</td>'+
                    '<td>'+value.nombre+'</td>'+
                    '<td>'+value.tipo+'</td>'+
                    // '<td>'+value.clasificacion+'</td>'+
                  '</tr>';
            }); 
            $('#diagnosticos_io').append(cuerpo_DIAGNOSTICOS_PRESTACION);
    }
    function agregarDiagnosticosAprocedimientos() {  
      var lista_diagnosticos = <?php echo json_encode($diagnosticos);?>;
      var cuerpo_DIAGNOSTICOS_PRESTACION = '' ;
      $.each(lista_diagnosticos, function (key, value) { 
        cuerpo_DIAGNOSTICOS_PRESTACION += '<option value="' + value.id_diagnostico + '">' + value.nombre + '</option>';
      }); 
      $(".select-diagnostico").html(cuerpo_DIAGNOSTICOS_PRESTACION);
    }
    

    // $(".deleteFilaCirujanoAnulado").click(function() {
    //   // Obtener el índice de la fila
    //   var index = $(this).closest("tr").index();
    //   // Obtener el campo oculto correspondiente
    //   var id_cirujano = $("#id_cirujano" + index);
    //   var id_cirujano2 = $("#id_cirujano2" + index);
    //   var id_cirujanoInput = $("#id_cirujano_anulado" + index);

    //   var cirujano = {
    //     id_cirujano: id_cirujano.val(),
    //   };

    //   // Verificar si la fila está marcada como eliminada
    //   if (id_cirujano.val() == id_cirujano2.val()) { //D->DELETE
    //       // Restablecer el valor del campo oculto
    //       id_cirujanoInput.val('');
    //       id_cirujano2.val('');
    //       // Eliminar la clase 'eliminado' para quitar el estilo de tachado
    //       $(this).closest("tr").removeClass("eliminado");
    //   } else {
    //       // Establecer el valor del campo oculto en 1
    //       id_cirujanoInput.val(JSON.stringify(cirujano));
    //       id_cirujano2.val(id_cirujano.val());
    //       // Agregar la clase 'eliminado' para aplicar el estilo de tachado
    //       $(this).closest("tr").addClass("eliminado");
    //   }
    // });

    $(".deleteFilaAnestesiologoAnulado").click(function() {
      // Obtener el índice de la fila
      var index = $(this).closest("tr").index();
      // Obtener el campo oculto correspondiente
      var id_anestesiologo = $("#id_anestesiologo" + index);
      var id_anestesiologo2 = $("#id_anestesiologo2" + index);
      var id_anestesiologoInput = $("#id_anestesiologo_anulado" + index);

      var anestesiologo = {
        id_anestesiologo: id_anestesiologo.val(),
      };

      // Verificar si la fila está marcada como eliminada
      if (id_anestesiologo.val() == id_anestesiologo2.val()) { //D->DELETE
          // Restablecer el valor del campo oculto
          id_anestesiologoInput.val('');
          id_anestesiologo2.val('');
          // Eliminar la clase 'eliminado' para quitar el estilo de tachado
          $(this).closest("tr").removeClass("eliminado");
      } else {
          // Establecer el valor del campo oculto en 1
          id_anestesiologoInput.val(JSON.stringify(anestesiologo));
          id_anestesiologo2.val(id_anestesiologo.val());
          // Agregar la clase 'eliminado' para aplicar el estilo de tachado
          $(this).closest("tr").addClass("eliminado");
      }
    });
    //ANULAR CIRUJANOS
    $('body').on('click', '.AnularFilaCirujano', function () {
      var id_medico = $(this).closest('tr').find('.AnularFilaCirujano').attr('data-id_cirujano_registro');
      var $tr = $(this).closest('tr');
      $('#modalAnularProfesional').data('tr', $tr); // Guardar el <tr> en el modal
      $('#modalAnularProfesional').data('cirujano', id_medico); 
      $('#anularFormularioProfesional').trigger("reset");
      $('#tituloFormularioProfesional-anular').html("Anular profesional que interviene en la operación");
      $('#modalAnularProfesional').modal('show');
      $('#id_medico').val(id_medico);
    });
    //ANULAR ANESTESIOLOGOS
    $('.AnularFilaAnestesiologos').on('click', function() {
      var id_medico = $(this).closest('tr').find('.AnularFilaAnestesiologos').attr('data-id_anestesiologo_registro');
      var $tr = $(this).closest('tr');
      $('#modalAnularProfesional').data('tr', $tr); // Guardar el <tr> en el modal
      $('#modalAnularProfesional').data('anestesiologo', id_medico); 
      $('#anularFormularioProfesional').trigger("reset");
      $('#tituloFormularioProfesional-anular').html("Anular profesional que interviene en la operación");
      $('#modalAnularProfesional').modal('show');
      $('#id_medico').val(id_medico);
    });

    $('#GuardarAnularProfesional').click(function (e) {
      e.preventDefault();
      var motivoAnulacion=$('#anulacion_motivo').val();
      
      if(motivoAnulacion == null || motivoAnulacion.length <=5){ 
        
        Swal.fire({
          title: "Verifique los campos ingresados",
          text: "Proceso cancelado. Ingrese un motivo de la anulación mayor a 5 caracteres para el campo motivo de anulación.",
          icon: "error",
          showConfirmButton: false,
          timer: 2500
        });
       
        $('#anulacion_motivo').addClass('borde-alerta');
        setTimeout(function() {
          $('#anulacion_motivo').focus();  
        }, 1000);
        return;
      }else{
        $('#anulacion_motivo').removeClass('borde-alerta');
      }
      var token=$('#_token').val();
      var route='{{url('motivo_anulacion_cirujano')}}';
      var $tr = $('#modalAnularProfesional').data('tr'); 
      var cirujano = $('#modalAnularProfesional').data('cirujano');
      var anestesiologo = $('#modalAnularProfesional').data('anestesiologo');
      
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

              var formData = new FormData($("#anularFormularioProfesional") [0]);
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

                          $('#anularFormularioProfesional').trigger("reset");
                          $('#modalAnularProfesional').modal('hide');
                          $tr.addClass('eliminado');
                          if(cirujano){
                            $('button[data-cirujano="' + cirujano + '"]').hide();
                          }
                          if(anestesiologo){
                            $('button[data-anestesiologo="' + anestesiologo + '"]').hide();
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
      $('#modalAnularProfesional').modal('hide');
    });

    $('#btnCancelar').on('hidden.bs.modal', function () {
      $('#modalAnularProfesional').modal('handleUpdate');
      $('body').addClass('modal-open'); 
    });
    $('#modalAnularProfesional').on('hidden.bs.modal', function () {
      $('#modalAnularProfesional').modal('handleUpdate');
      $('body').addClass('modal-open'); 
    });

    function sumarValores(enfermeriaunoID, enfermeriadosID, totalID, totalxID) {
        var valorUno = parseFloat($(enfermeriaunoID).val()) || 0;
        var valorDos = parseFloat($(enfermeriadosID).val()) || 0;
        var suma = valorUno + valorDos;
        $(totalID).val(suma);
        $(totalxID).val(suma);
    }
    $('#enfermeriauno0, #enfermeriados0').on('input', function() {
        sumarValores('#enfermeriauno0', '#enfermeriados0', '#total0', '#totalx0');
    });
    $('#enfermeriauno1, #enfermeriados1').on('input', function() {
        sumarValores('#enfermeriauno1', '#enfermeriados1', '#total1', '#totalx1');
    });

    $('#modal-detalle-intraoperatorio').on('hidden.bs.modal', function () {
      // Restaurar el primer modal si es necesario
      $('#modal-intraoperatorio').css('z-index', '1050'); // Reajustar si lo deseas
    });
    function setTodayDate() {
      var today = new Date();
      var year = today.getFullYear();
      var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Agregar 0 si el mes tiene un solo dígito
      var day = today.getDate().toString().padStart(2, '0'); // Agregar 0 si el día tiene un solo dígito

      var formattedDate = year + '-' + month + '-' + day;

      // Asignar la fecha actual al input
      document.getElementById('fecha_registro').value = formattedDate;
    }
    function setCurrentTime() {
        var now = new Date();
        var hours = now.getHours().toString().padStart(2, '0'); // Asegurar dos dígitos para la hora
        var minutes = now.getMinutes().toString().padStart(2, '0'); // Asegurar dos dígitos para los minutos

        var formattedTime = hours + ':' + minutes;

        // Asignar la hora actual al input
        document.getElementById('hora_registro').value = formattedTime;
    }
  });

  function generarCodigoSiteds() {
    const DNI = <?php echo $asegurado['nro_doc_ident']; ?>; 
    console.log(DNI);  // Verificar si tiene un valor correcto
      // Mostrar el SweetAlert2 de carga
    Swal.fire({
      title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
      html: "<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // Validación previa a la generación de código  
    const isValidate = validateGenerateSiteds();
    if (isValidate) {
      Swal.hideLoading();
      Swal.fire({
        icon: 'error',
        title: 'Validación fallida',
        text: 'La validación del formulario falló. Corrige los errores.',
        confirmButtonText: 'Aceptar'
      });
      return;  // Detenemos la ejecución si la validación falla
    }

    // Deshabilitar input y limpiar el valor
    // $("#cod_siteds_susalud2").prop("readonly", true);
    // $("#cod_siteds_susalud2").val("");

    // Crear el cuerpo de la solicitud
    const ipressJson = <?php echo json_encode($ipress);?>;
    const body = {
      "idRemitente": ipressJson.codigo,
      "nuRucRemitente": "",
      "idCorrelativo": '00000001',
      "tiDoPaciente": <?php echo $asegurado['tipo_doc_ident']?>,
      "nuDoPaciente": <?php echo $asegurado['nro_doc_ident']?>,
      "noResponsableAut": "",
      "noPaResponsableAut": "",
      "noMaResponsableAut": "",
      "nuDoResponsableAut": "",
      "coAdmisionista": <?php echo $asegurado['nro_doc_ident']?>,
      "beMaxInicial": "0",
      "coTiMoneda": "1",
      "coPagoFijo": "0",
      "coCalServicio": "VS",
      "coPagoVariable": "100",
      "flagCaGarantia": "0",
      "deFlagCartaGarantia": "Sigesapol 0.1",
      "tipoAtencionId": 3,
      "servicioId": <?php echo !empty($asegurado['id_servicio'])?$asegurado['id_servicio']:(!empty($datos_intervencion['id_servicio'])?$datos_intervencion['id_servicio']:'')?>,
      "forceTempCodeGeneration": parseInt($("#statusResponseWsSusalud").val()) === 1 ? true : false
    };

    console.log(body);
    // Hacer la solicitud AJAX
    $.ajax({
      url: '/ws-dirsapol-siteds',
      type: 'POST',
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(body),
      success: (result, textStatus, jqXHR) => {
        //$("#cod_siteds_susalud2").val(result.nuAutorizacion);
        $("#cod_siteds_susalud").val(result.nuAutorizacion);

        // Actualizar el modal a éxito
        Swal.fire({
          icon: 'success',
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS Generado</span></div>",
          showConfirmButton: false,
          timer: 1500
        });
      },
      error: (xhr, status, error) => {
        $("#cod_siteds_susalud").val("");
       // $("#cod_siteds_susalud2").val("");
       // $("#cod_siteds_susalud2").prop("readonly", false); 

        // Obtener el mensaje de error
        const messageError = xhr.responseJSON && xhr.responseJSON.message 
          ? xhr.responseJSON.message 
          : "Servicio no disponible. Intentelo en unos minutos.";

        // Mostrar el modal de error
        Swal.fire({
          icon: 'error',
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
          html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
          confirmButtonText: 'ENTENDIDO',
          customClass: 'swal-wide',
        });
      },
      complete: () => {
        // Ocultar el loading después de que la solicitud ha terminado
        Swal.hideLoading();
      }
    });
  }
  function validateGenerateSiteds() {
    const nro_doc_ident = $("#nro_doc_ident").val();

    let messageValidate = "";

    if (!nro_doc_ident || parseInt(nro_doc_ident) <= 0) {
      messageValidate += `<p> - Es necesario el numero de documento de identidad.</p>`;
    }
    
    if (messageValidate !== null && messageValidate.length > 0) {
      Swal.fire({
        icon: "error",
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
        html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 14px; text-align: left'>${messageValidate}</span></div>`,
        confirmButtonText: 'ENTENDIDO',
        customClass: 'swal-wide',
      });
      return true;
    }
    return false;
  }
 
</script>

<div id="blur-container">
  

<div style=" font-size: 11px" class="panel-body">
  <form id="formIntraoperario" method="POST" action="{{ route('guardar_intraoperario') }}">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id_prestacion" value="{{!empty($asegurado['id_prestacion'])?$asegurado['id_prestacion']:''}}">
<input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
<input type="hidden" name="nro_doc_ident" id="nro_doc_ident" value="{{$asegurado['nro_doc_ident']}}">
{{--<input type="hidden" name="cod_siteds_susalud" id="cod_siteds_susalud" value="{{$codigo_siteds}}">--}}
<input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="{{$id_atencion}}">
<input type="hidden" name="id_asegurado" id="id_asegurado" value="{{$asegurado['id_asegurado']}}">
<input type="hidden" name="tipo_real" id="tipo_real">
<input type="hidden" name="id_intervencion" id="id_intervencion" value="{{$asegurado['id_intervencion']}}">



<div class="panel panel-info contenedor-datos-generales">
<div class="panel-heading">
<h3 class="panel-title size-titulo">
DATOS DE LA ATENCIÓN
</h3>
</div>
<div class="panel-body">
<div class="row intro-contenedor">
<div class="col-lg-3">
<label>TIPO DE ATENCION</label>
<input type="text" readonly="" value="HOSPITALIZACIÓN" class="form-control">
</div>
<!-- <div class="col-lg-3">
  <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
  <div class="">
    <input type="text" class="form-control"  id="cod_siteds_susalud" name="cod_siteds_susalud" value="{{$codigo_siteds}}"  maxlength="12">
    <span class="invalid-feedback" style="display: none; color: red;">
        {{ $errors->first('cod_siteds_susalud2') }}
    </span>
  </div>
</div> -->
<div class="col-md-3">
  <label for="">SERVICIO</label>
  <input type="text" name="servicio" class="form-control" value="<?php echo isset($hospitalizacion->servicio_cargo)?$hospitalizacion->servicio_cargo:$servicio->nombre;?>" style="text-align:left" readonly>
</div>
<div class="col-md-3">
    <label for="">SALA</label>
    <input type="text" name="sala" class="form-control" value="<?php echo isset($hospitalizacion->cama)?$hospitalizacion->cama: (isset($datos_intervencion->sala) ? $datos_intervencion->sala : '');?>" style="text-align:left" readonly>
</div>
<div class="row intro-contenedor">
<div class="col-lg-2">
<label>FECHA DE REGISTRO</label>
<input type="date" class="form-control datepicker" style="height: 28px !important;font-size: 12px !important" name="fecha_registro" id="fecha_registro" autocomplete="off" >
<span class="invalid-feedback" style="display: none; color: red;">
    {{ $errors->first('fecha_registro') }}
</span>
</div>
<div class="col-lg-2">
<label>HORA DE REGISTRO</label>
<input type="time" class="form-control"  name="hora_registro" id="hora_registro">
<span class="invalid-feedback" style="display: none; color: red;">
    {{ $errors->first('hora_registro') }}
</span>
</div>
<div class="col-lg-2">
<label>CÓDIGO UPSS</label>
<input type="text" class="form-control" readonly=""  value="<?php echo isset($datos_servicio->codigo)?$datos_servicio->codigo:(isset($datos_upss_ambulatorio->codigo) ? $datos_upss_ambulatorio->codigo : (!empty($hospitalizacion->codigo_upss)?$hospitalizacion->codigo_upss:''));?>" name="codigo_upss" id="codigo_upss">
</div>
<div class="col-lg-3">
<label>NOMBRE DE LA UPSS</label>
<input type="text" class="form-control" readonly=""  value="<?php echo isset($datos_servicio->descripcion_upss)?$datos_servicio->descripcion_upss:(isset($datos_upss_ambulatorio->descripcion_upss) ? $datos_upss_ambulatorio->descripcion_upss : (!empty($hospitalizacion->descripcion_upss)?$hospitalizacion->descripcion_upss:''));?>" name="nombre_upss" id="nombre_upss">
</div>
<div class="col-lg-3">
<label>NOMBRE DE LA IPRESS</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['codigo_ipress']}} - {{$asegurado['establecimiento']}}" name="nombre_ipress" id="nombre_ipress">
</div>
</div>
</div>
</div>
<div class="panel panel-info">
<div class="panel-heading">
<h3 class="panel-title size-titulo">
INFORMACIÓN DEL PACIENTE
</h3>
</div>
<div class="panel-body">
<div class="row intro-contenedor">
<div class="col-lg-2">
<label>DOCUMENTO DE IDENTIDAD</label>
<input type="text" readonly="" value="DNI: {{$asegurado['nro_doc_ident']}}" name="documento_identidad" id="documento_identidad" class="form-control">
</div>
<div class="col-lg-2">
<label class="control-label">APELLIDO PATERNO</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['asegurado_paterno']}}" name="asegurado_apellido_paterno" id="asegurado_apellido_paterno">
</div>
<div class="col-lg-2">
<label class="control-label">APELLIDO MATERNO</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['asegurado_materno']}}" name="asegurado_apellido_materno" id="asegurado_apellido_materno">
</div>
<div class="col-lg-3">
<label class="control-label">NOMBRES</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['asegurado_nombre']}} " name="asegurado_nombres" id="asegurado_nombres">
</div> 
<div class="col-lg-3">
<label>PARENTESCO</label>
<input type="text" class="form-control" readonly="" value="{{$asegurado['parentesco']}}"  name="parentesco" id="parentesco">
</div>
</div>
<div class="row intro-contenedor">
<div class="col-lg-1">
<label>F. DE NACIMIENTO</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['fecha_nac']}}"  name="fecha_nacimiento" id="fecha_nacimiento">
</div>
<div class="col-lg-1">
<label>CIP</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['cip']}}" name="cip" id="cip">
</div>
<div class="col-lg-2">
<label>GRADO</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['grado_asegurado']}}" name="grado" id="grado">
</div>
<div class="col-lg-2">
<label>SITUACIÓN</label>
<input type="text" class="form-control" readonly=""   name="{{$asegurado['situacion_paciente']}}" id="situacion">
</div>
<div class="col-lg-1">
<label>SEXO</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['sexo']}}" name="sexo" id="sexo">
</div>
<div class="col-lg-1">
<label>EDAD</label>
<input type="text" class="form-control" readonly=""  value="{{$asegurado['edadafiliado']}}" name="edad" id="edad">
</div>
<div class="col-lg-1">
<label>T. SANGRE</label>
<input type="text" class="form-control" readonly="" value="{{$asegurado['tipo_sangre']}}" name="tipo_sangre" id="tipo_sangre">
</div>
<div class="col-lg-3">
<label>DIRECCIÓN</label>
<input type="text" class="form-control" readonly="" value="{{$asegurado['domicilio']}}"   name="direccion" id="direccion">
</div>
</div>
</div>
</div>
<div class="panel panel-info contenedor-datos-generales">
<div class="panel-heading">
<h3 class="panel-title size-titulo">
PROFESIONALES QUE INTERVIENEN EN LA OPERACIÓN
</h3>
</div>
<div class="panel-body">

<br />
<div class="row intro-contenedor">
<div class="col-lg-3" style="padding-bottom: 1rem;">
<button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addCirujanoIntraoperatorio">Cirujanos que Intervienen Operación</button>
<span class="invalid-feedback" style="display: none; color: red;">
  {{ $errors->first('addCirujanoIntraoperatorio') }}
</span>
</div>
<div class="col-lg-12">
<table class="table table-bordered" cellspacing="0" width="100%" id="tblCirujano" cellspacing="0" width="100%" style="margin-top:5px;" >
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
    <button type="button"  class="btn btn-danger AnularFilaCirujano btn-xs" data-id_cirujano_registro="{{ $row->id }}" data-toggle="modal" style="margin-left:4px">
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
</div>

<div class="row intro-contenedor">
<div class="col-lg-3" style="padding-bottom: 1rem;">
<button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addAnestesiologo">Anestesiologos que Intervienen Operación</button>
<span class="invalid-feedback" style="display: none; color: red;">
  {{ $errors->first('addAnestesiologo') }}
</span>
</div>
<div class="col-lg-12">
<table class="table table-bordered" id="tblAnestesiologo" cellspacing="0" width="100%" style="margin-top:5px;">
<tbody>

@if(!empty($anetesiologos))
  @foreach($anetesiologos as $key => $row)
  <?php
  $isEliminadoAnestesiologo = !empty(old('id_anestesiologo_anulado.'.$key))|| ($row->estado == 3);
  ?>
<tr class="<?php echo $isEliminadoAnestesiologo ? 'eliminado' : ''; ?>">
  <td>
    {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} 
  </td>
  <td>
    {{$row->descripcion}}
  </td>
  @if($row->estado == 0 || $row->estado == 1)
  <td>
    <button type="button"  class="btn btn-danger AnularFilaAnestesiologos btn-xs" data-id_anestesiologo_registro="{{ $row->id }}" data-toggle="modal" style="margin-left:4px">
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
</div>

<div class="row intro-contenedor">
<div class="col-lg-3" style="padding-bottom: 1rem;">
<button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addEnfermeriaInstrumentista">Enf. Instrumentista Intervienen Operación</button>
<span class="invalid-feedback" style="display: none; color: red;">
  {{ $errors->first('addEnfermeriaInstrumentista') }}
</span>
</div>
<div class="col-lg-12">
<table class="display" id="tblEnfermeriaInstrumentista" cellspacing="0" width="100%" style="margin-top:5px;">
<tbody>

</tbody>
</table>
</div>
</div>
</div>
</div>
<ul class="nav nav-tabs">
<li class="active"><a href="#tab1default" data-toggle="tab" style="font-size:12px">REGISTRO DE PACIENTE EN CENTRO QUIRÚRGICO</a></li>
<li><a href="#tab2default" data-toggle="tab" style="font-size:12px">DIAGNÓSTICO DE ENFERMERÍA</a></li>
<li><a href="#tab3default" data-toggle="tab" style="font-size:12px">BALANCE DE FLUIDOS</a></li>
<li><a href="#tab4defaultintraop" data-toggle="tab" style="font-size:12px">RECUENTO DE GASAS</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane fade in active" id="tab1default">
<fieldset>
<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-body">
<div class="form-group">
<div class="col-lg-6">
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">RECEPCIÓN DEL PACIENTE</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
        <input type="text" id="recepcion_paciente_descripcion" class="form-control" name="recepcion_paciente_descripcion">
        <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('recepcion_paciente_descripcion') }}
        </span>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="recepcion_paciente_tiempo" name="recepcion_paciente_tiempo" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('recepcion_paciente_tiempo') }}
          </span>
      </div>
  </div>
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">INICIO DE ANESTESIA</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="anestesia_inicio" name="anestesia_inicio" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('anestesia_inicio') }}
          </span>
      </div>
  </div>
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">INICIO DE CIRUGÍA</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="cirugia_inicio" name="cirugia_inicio" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('cirugia_inicio') }}
          </span>
      </div>
  </div>
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">SALIDA DE PACIENTE</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="salida_paciente" name="salida_paciente" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('salida_paciente') }}
          </span>
      </div>
  </div>
  {{--<div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">INICIO DE PACIENTE</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="inicio_paciente" name="inicio_paciente" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('inicio_paciente') }}
          </span>
      </div>
  </div>
  --}}
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">HORA DE LLEGADA URPA</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="hora_llegada_urpa" name="hora_llegada_urpa" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('hora_llegada_urpa') }}
          </span>
      </div>
  </div>
</div>
<div class="col-lg-6">
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">TIPO DE ANESTESIA</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <select id="tipo_anestesia" name="tipo_anestesia" class="form-control" style="width: 100%">
              <option value="">Seleccionar</option>
              @foreach($tipo_anestesia as $valor)
              <option value="{{$valor->id}}"  {{ (isset($datos_intervencion->id_tipo_anestesia) && $datos_intervencion->id_tipo_anestesia == $valor->id) ? 'selected' : '' }}>{{$valor->descripcion}}</option>
              @endforeach;
          </select>
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('tipo_anestesia') }}
          </span>
      </div>
  </div>
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">TÉRMINO DE ANESTESIA</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="anestesia_termino" name="anestesia_termino" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('anestesia_termino') }}
          </span>
      </div>
  </div>
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">TÉRMINO DE CIRUGÍA</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="cirugia_termino" name="cirugia_termino" min="08:00" max="18:00" />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('cirugia_termino') }}
          </span>
      </div>
  </div>
  <div class="row">
      <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
          <label style="margin-left: 2rem;">TIEMPO REAL</label>
      </div>
      <div class="col-lg-4" style="padding-bottom: 1rem;">
          <input type="time" class="form-control" style="width: 100%;" id="tipo_real2" name="tipo_real2" min="08:00" max="18:00" disabled />
          <span class="invalid-feedback" style="display: none; color: red;">
            {{ $errors->first('tipo_real') }}
          </span>
      </div>
  </div>
 

<div class="row">
  <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">OPERACIÓN PROGRAMADA</label>
      <input type="hidden" name="tipo_operacion" value="Operación Programada" >
  </div>
  <div class="col-lg-8" style="padding-bottom: 1rem;">
    <input type="text" class="form-control" name="operacion_programada" id="operacion_programada" maxlength="250">
  </div>
</div>

<div class="row">
  <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">OPERACIÓN EMERGENCIA</label>
      <input type="hidden" name="tipo_operacion" value="Operación de emergencia">
  </div>
  <div class="col-lg-8" style="padding-bottom: 1rem;">
    <input type="text" class="form-control" name="operacion_emergencia" id="operacion_emergencia" maxlength="250">
  </div>
</div>

  <div class="row">
    <div class="col-lg-4" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
        <label style="margin-left: 2rem;">OPERACIÓN REALIZADA</label>
    </div>
    <div class="col-lg-8" style="padding-bottom: 1rem;">
      <input type="text" class="form-control" name="operacion_realizada" id="operacion_realizada" maxlength="250">
    </div>
  </div>
</div>
<div class="row" style="padding-bottom: 1rem;">
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">COMPLICACIONES</label>
  </div>
  <div class="col-lg-8" style="align-content: center; padding-bottom: 1rem;">
      <textarea class="form-control" cols="5" id="complicaciones" name="complicaciones" placeholder="Descripción de las complicaciones"></textarea>
  </div>
</div>
<div class="row" style="padding-bottom: 1rem;">
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">CAJAS DE INSTRUMENTAL</label>
  </div>
  <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
      <input type="text" class="form-control" name="cajas_instrumental_descripcion" id="cajas_instrumental_descripcion" />
  </div>
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">DISPONIBILIDAD DE</label>
  </div>
  <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
      <input type="hidden" name="cajas_instrumental_equipo" value="0">
      <input type="checkbox" id="cajas_instrumental_equipo" name="cajas_instrumental_equipo" value="1" ><span class="checkboxtexto" style="margin-left: 2rem; font-weight: 700;">EQUIPOS</span>
  </div>
  <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
      <input type="hidden" name="cajas_instrumental_insumos" value="0">
      <input type="checkbox" id="cajas_instrumental_insumos" name="cajas_instrumental_insumos" value="1"><span class="checkboxtexto" style="margin-left: 2rem; font-weight: 700;">INSUMOS</span>
  </div>
  <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
      <input type="hidden" name="cajas_instrumental_instrumental" value="0">
      <input type="checkbox" id="cajas_instrumental_instrumental" name="cajas_instrumental_instrumental" value="1"><span class="checkboxtexto" style="margin-left: 2rem; font-weight: 700;">INSTRUMENTAL</span>
  </div>
</div>
<div class="row" style="padding-bottom: 1rem;">
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">ENF I Y ENF II VERIFICA Y CONTROLA</label>
  </div>
  <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
      <input type="hidden" id="enf1_enf2_vc_insumos" name="enf1_enf2_vc_insumos" value="0">
      <input type="checkbox" id="enf1_enf2_vc_insumos" name="enf1_enf2_vc_insumos" value="1"><span class="checkboxtexto" style="margin-left: 2rem; font-weight: 700;">INSUMOS</span>
  </div>
  <div class="col-lg-2" style="align-content: center; padding-bottom: 1rem;">
      <input type="hidden" id="enf1_enf2_vc_instrumental" name="enf1_enf2_vc_instrumental" value="0">
      <input type="checkbox" id="enf1_enf2_vc_instrumental" name="enf1_enf2_vc_instrumental" value="1"><span class="checkboxtexto" style="margin-left: 2rem; font-weight: 700;">INSTRUMENTAL</span>
  </div>
</div>
<div class="row" style="padding-bottom: 1rem;">
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">MAQUINA DE ANESTESIA</label>
  </div>
  <div class="col-lg-3" style="padding-bottom: 1rem;">
      <input type="text" class="form-control"  name="maquina_anestesia_descripcion" id="maquina_anestesia_descripcion" />
  </div>
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">OXIGENO</label>
  </div>
  <div class="col-lg-3" style="padding-bottom: 1rem;">
      <input type="text" class="form-control"  name="oxigeno_descripcion" id="oxigeno_descripcion" />
  </div>
</div>

<div class="row" style="padding-bottom: 1rem;">
  <div class="col-lg-2" style="align-content: center; height: 4.5rem; padding-bottom: 1rem;">
      <label style="margin-left: 2rem;">COMENTARIOS</label>
  </div>
  <div class="col-lg-8" style="align-content: center; padding-bottom: 1rem;">
      <textarea class="form-control" cols="5" id="comentarios" name="comentarios" placeholder="Comentarios"></textarea>
  </div>
</div>
</div>
</div>
</div>
</fieldset>
</div>
<div class="tab-pane fade" id="tab2default">
<fieldset>
<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-body">
<div class="form-group">
  <div class="col-lg-6" style="display: flex; justify-content: center;">
    <div style="width: 80%; background-color: rgb(250, 250, 250); border: 1px solid rgb(226, 226, 226);">
        <div style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
            <p style="margin: 0; text-align: center; font-weight: 700;">DIAGNÓSTICO DE ENFERMERIA</p>
          
            <div id="diagnosticoError" style="color: red;"></div>
            
        </div>
      @foreach ($diagnosticos_enfermeria as $key => $diagnostico)
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem;">
          <div style="display: flex; align-items: flex-start; width: 100%;">
            <span style="font-weight: 700;">{{ $diagnostico->descripcion }}</span>
            <input type="hidden" id="diagnostico" name="diagnostico[]" value="{{ $diagnostico->id }}">
          </div>
          <div style="display: flex; align-items: flex-start; justify-content: flex-end; width: 10%;">
          <input type="checkbox" name="diagnostico_estado[]" value="{{ $diagnostico->id }}">
        </div>
        </div>
      @endforeach
        
    </div>
  </div>
  <div class="col-lg-6">

    <div style="width: 100%; border: 1px solid rgb(226, 226, 226);">
        <div style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
            <p style="margin: 0; text-align: center; font-weight: 700;">INTERVENCION DE ENFERMERIA (NIC)</p>
            <div id="nicError" style="color: red;"></div>
        </div>  
        @foreach ($intervencion_enfermeria as $key => $nic)
          <div style="border-bottom: 1px solid rgb(226, 226, 226); padding: 1rem 0;">
              <div style="display: flex; padding: 0 1rem;">
                  <div style="display: flex; align-items: flex-start;">
                      <span style="font-weight: 700; padding-right: 1rem;">{{ $nic->descripcion }}</span>
                      <input type="hidden" id="nics_padre" name="nics_padre[]" value="{{ $nic->id}}">
                  </div>

                  <div style="display: flex; flex-direction: column;">
                      @if(!empty($nic->hijos))
                      <input type="hidden" name="nic_estado[{{ $nic->id }}]" value="">
                          @foreach ($nic->hijos as $hijo)
                              <div>
                                  <input type="hidden" id="nics" name="nics[{{ $nic->id }}][]" value="{{ $hijo['id'] }}">
                                  <label>
                                    <input type="checkbox"  id="nic_estado" name="nic_estado[{{ $nic->id}}][]" value="{{ $hijo['id'] }}">
                                      <span class="checkboxtexto" style="margin-left: 2rem; font-weight: 700;">{{ $hijo['descripcion'] }}</span>
                                  </label>
                              </div>
                          @endforeach
                      @endif
                  </div>
              </div>
          </div>
        @endforeach
    </div>

  </div>
</div>
</div>
</div>
</fieldset>
</div>
<div class="tab-pane fade" id="tab3default">
<fieldset>
<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-body">
<div class="form-group">
<div class="row intro-contenedor" style="padding: 0 1rem;">
  <div class="row intro-contenedor">
    <div class="col-lg-3" style="padding-bottom: 1rem;">
      <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addBalanceFluidos">Agregar Balance de Fluidos</button>
      <span class="invalid-feedback" style="display: none; color: red;">
        {{ $errors->first('addBalanceFluidos') }}
      </span>
    </div>
    <div class="col-lg-12">
      <table class="display" id="tblBalanceFluidos" cellspacing="0" width="100%" style="margin-top:5px;">
        <thead>
          <tr>
            <th width="20%">INGRESO</th>
            <th width="60%">EGRESO</th>
            <th width="20%">TEREAPEUTICA</th>
          </tr>
        </thead>
        <tfoot>
        </tfoot>
        <tbody>
        
        </tbody>
      </table>
    </div>
  </div>

  {{-- <div class="col-lg-3" style="padding-bottom: 1rem;">
      <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="">Agregar Balance de Fluidos</button>
  </div>
  <div class="col-lg-12">
      <table style="width: 100%;">
          <tr>
              <th style="width: 20%;">INGRESO</th>
              <th style="width: 20%;">EGRESO</th>
              <th style="width: 20%;">TEREAPEUTICA</th>
          </tr>
          <tr>
              <td>
                  &nbsp;<input type="text" data-toggle="tooltip" data-placement="top"  name="" readonly id="" class="form-control input-sm" style="text-align:left" />
              </td>
              <td style="padding-left: 1rem;">
                  &nbsp;<input type="text" data-toggle="tooltip" data-placement="top"  name="" readonly id="" class="form-control input-sm" style="text-align:left" />
              </td>
              <td style="padding-left: 1rem;">
                  &nbsp;<input type="text" data-toggle="tooltip" data-placement="top"  name="" readonly id="" class="form-control input-sm" style="text-align:left" />
              </td>
              <td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
          </tr>
      </table>
  </div> --}}
</div>
</div>
</div>
</div>
</fieldset>
</div>
<div class="tab-pane fade" id="tab4defaultintraop">
<fieldset>
<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-body">
<div class="form-group">
<div class="row intro-contenedor" style="padding: 0 1rem;">
  <div class="col-lg-3" style="padding-bottom: 1rem;">
      <!-- <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="">Agregar Balance de Fluidos</button>-->
  </div>
  <div class="col-lg-12">
      <table class="table table-bordered" style="width: 100%;">
          <tr>
              <th style="width: 20%;">&nbsp;</th>
              <th style="width: 20%;">ENFERMERÍA I</th>
              <th style="width: 20%;">ENFERMERÍA II</th>
              <th style="width: 20%;">TOTAL</th>
          </tr>
          <tr>
              <td>
                  &nbsp;GASA GRANDE
                  <input type="hidden" name="tipo_gasa[]" value="1">
              </td>
              <td>
                <input type="number" data-toggle="tooltip" data-placement="top"  name="enfermeriauno[]" id="enfermeriauno0" class="form-control ui-autocomplete-input" style="height: 28px !important;font-weight: 800;" required="" autocomplete="off">
                <span class="invalid-feedback" style="display: none; color: red;">
                  {{ $errors->first('enfermeria1') }}
                </span>
              </td>
              <td>
                <input type="number" data-toggle="tooltip" data-placement="top"  name="enfermeriados[]" id="enfermeriados0" class="form-control ui-autocomplete-input" style="height: 28px !important;font-weight: 800;" required="" autocomplete="off">
                <span class="invalid-feedback" style="display: none; color: red;">
                  {{ $errors->first('enfermeria2') }}
                </span>
              </td>
              <td>
                <input type="hidden" name="enfermeriatres[]" id="total0">
                <input type="number" data-toggle="tooltip" data-placement="top"  id="totalx0" class="form-control ui-autocomplete-input" style="height: 28px !important;font-weight: 800;" readonly>
                <span class="invalid-feedback" style="display: none; color: red;">
                  {{ $errors->first('enfermeria3') }}
                </span>
              </td>
          </tr>
          <tr>
              <td>
                  &nbsp;GASA CHICA
                  <input type="hidden" name="tipo_gasa[]" value="2">
              </td>
              <td>
                <input type="number" data-toggle="tooltip" data-placement="top"  name="enfermeriauno[]" id="enfermeriauno1" class="form-control ui-autocomplete-input" style="height: 28px !important;font-weight: 800;" required="" autocomplete="off">
              </td>
              <td>
                <input type="number" data-toggle="tooltip" data-placement="top"  name="enfermeriados[]" id="enfermeriados1" class="form-control ui-autocomplete-input" style="height: 28px !important;font-weight: 800;" required="" autocomplete="off">
              </td>
              <td>
                <input type="hidden" name="enfermeriatres[]" id="total1">
                <input type="number" data-toggle="tooltip" data-placement="top"  id="totalx1" class="form-control ui-autocomplete-input" style="height: 28px !important;font-weight: 800;" readonly>
              </td>
          </tr>
      </table>
  </div>
</div>
</div>
</div>
</div>
</fieldset>
</div>
</div>
<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-heading">
<h3 class="panel-title size-titulo">
PROCEDIMIENTOS DE INTERVENCIÓN
</h3>
</div>
<div class="panel-body">
<table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%" style="margin-top:5px;">
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

<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-heading">
<h3 class="panel-title size-titulo">
DIAGNOSTICO DE LA ATENCION
</h3>
</div>
<div class="panel-body">
<table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%" style="margin-top:5px;">
<thead>
<tr>
<th width="15%">Codigo</th>
<th width="50%">DESCRIPCION</th>
<th width="15%">Tipo</th>
<!-- <th width="15%">Clasificación</th> -->
</tr>
</thead>
<tfoot>
</tfoot>
<tbody id="diagnosticos_io">

</tbody>
</table>
</div>
</div>

{{-- PROCEDIMIENTO --}}
<div class="panel panel-info panel-bordered panel-sm">
<div class="panel-heading">
<h3 class="panel-title size-titulo">
PROCEDIMIENTOS
</h3>
</div>
<div class="panel-body">
<div class="form-group">
<div class="row intro-contenedor">
<div class="row intro-contenedor">
<div class="col-lg-3" style="padding-bottom: 1rem;">
<button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addProcedimiento">Agregar Procedimiento</button>
<span class="invalid-feedback" style="display: none; color: red;">
  {{ $errors->first('addProcedimiento') }}
</span>
</div>

<div class="col-lg-12">
<table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
<thead>
<tr>
<th width="45%">Procedimiento</th>
  <th width="5%">Cantidad</th>
  <th width="45%">Diagnosticos</th>
  <th width="5%"></th>
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
</div>
<div class="row" style="padding: 1.5rem 0;">
<div class="col-sm-10"></div>
<div class="col-sm-2">

<input  type="button" value="GUARDAR" class="form-control btn btn-success" id="btnGuardarIntraOperatorio" style="text-align:center!important">
</div>
</div>
</form>
</div>


<div class="modal fade" id="modalAnularProfesional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        >&times;</span></button>
                <b id="tituloFormularioProfesional-anular"></b>
            </div>

            <div class="modal-body">
                <form id="anularFormularioProfesional" name="anularFormularioProfesional" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_medico" name="id_medico">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                          <h2>Motivo de Anulación</h2>
                          <textarea id="anulacion_motivo" class="form-control" name="anulacionMotivo" rows="4" cols="50" placeholder="Escribe el motivo de la anulación..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCancelar" class="btn btn-danger pull-left">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="GuardarAnularProfesional" value="create">Anular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>