<div id="uploadModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 8px; width: 300px; text-align: center;">
      <h4>Subiendo archivo...</h4>
      <div style="background-color: #e0e0e0; border-radius: 5px; overflow: hidden; margin-top: 10px;">
          <div id="progressBar" style="width: 0%; height: 20px; background-color: #4CAF50; text-align: center; color: white;">0%</div>
      </div>
      <p id="progressText" style="margin-top: 10px;">0%</p>
  </div>
</div>


<style>
  
  .modal-header, h4, .close{
    font-size: 18px !important;
  }

  .panel-heading div {
    margin-top: auto !important;
  }

  .table-sortable tbody tr {
    cursor: move;
  }

  #global {
    min-height: 650px !important;
    margin: 15px;
  }

  .margin {

    margin-bottom: 20px;
  }

  .margin-buscar {
    margin-bottom: 5px;
    margin-top: 5px;
  }

  .row {
    margin-top: 10px;
    padding: 0 10px;
  }

  .clickable {
    cursor: pointer;
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

  .dataTables_filter {
    display: none;
  }

  .help-block {
    display: block;
    margin-top: 1px;
    margin-bottom: 10px;
    color: #737373;
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

  .custom-form-group {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
  }

  .custom-form-group label {
    width: 100px; /* Ajusta el ancho según sea necesario */
    margin-bottom: 0;
  }

  .custom-form-group input {
    flex: 1;
  }

  .form-control.cant-input-sm{
    width: 65px;
    margin-left: -180%;
    height: 26px;
 }

  .cant{
    margin-left:16.5%;
    font-size: 17px;
  }

  .titulo-modal{
    margin-left: -76%;
  }
  .panel-heading2 {
    padding: 15px ;
    color: #3c763d;
    background-color: #e0e9e0;
    border-color: #e0e9e0;
  }

    /*td a.tooltip-custom,
    td a.tooltip-custom:focus,
    td a.tooltip-custom:hover {
        color: black;
        text-decoration: none;
    }*/

    .tooltip-inner {
        background-color: #ffffff; /* Color de fondo personalizado */
        color: #000000; /* Color del texto */
        border: 2px solid #3c763d; /* Borde opcional */
        width: 100% !important;
    }

    .item-tooltip {
        width: 100%;
        text-align: left;
    }
  /**********Imagenes Inicio**********/
  .btn-ready {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
  }
  
  .btn-ready:focus, .btn-ready:hover {
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
  }

  .btn-ready:before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 300%;
      height: 300%;
      background: rgba(255, 255, 255, 0.2);
      transition: all 0.6s ease;
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0);
  }

  .btn-ready:hover:before {
      transform: translate(-50%, -50%) scale(1);
  }

  .pdfView {
    margin: auto !important;
    display: block !important;
    width: 850px !important;
    height: 100vh !important;
    border-radius: 20px !important;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)
  }
  /**Imagenes Fin**/
  .smaller-swal {
    width: 300px !important; /* Ajusta el ancho */
    font-size: 14px !important; /* Ajusta el tamaño de la fuente */
  }
  .smaller-swal .swal2-title {
      font-size: 18px !important; /* Tamaño de la fuente del título */
  }
  .smaller-swal .swal2-icon {
      font-size: 24px !important; /* Ajusta el tamaño del ícono */
  }

  .swal2-warning {
    font-size: 1rem !important;
  }
  .small-swal-icon {
    width: 10.5rem !important;    /* Ajusta el ancho del ícono */
    height: 10.5rem !important;   /* Ajusta la altura del ícono */
    font-size: 4.2rem !important; /* Ajusta el tamaño de la letra dentro del ícono */
    line-height: 10.5rem !important; /* Centra el contenido en el ícono */
  }
  
  
</style>

<script>
  $(document).ready(function() {
    obtenerFormatosSubidos();
    formatos();
    $('#btnSubirArchivo').on('click', function(event) {
        event.preventDefault(); 

        var archivo = $('#archivo')[0]?.files[0];
        var tipoFormato = $('#tipo_formato').val();

        if (!archivo) {
          Swal.fire({
              title: "Información de Servicio de Registros",
              text: "Faltó seleccionar el archivo PDF",
              icon: "info",
              showConfirmButton: true,
              width: '30%',
              customClass: {
                  icon: 'small-swal-icon' // Aplica una clase personalizada al ícono
              },
              padding: '1.5rem',
          });
          return;
        }

        var tipoArchivo = archivo.type;
        if (tipoArchivo !== 'application/pdf') {
           Swal.fire({
              title: "Información de Servicio de Registros",
              text: "El archivo debe ser de tipo PDF",
              icon: "info",
              showConfirmButton: true,
              width: '30%',
              customClass: {
                  icon: 'small-swal-icon'
              },
              padding: '1.5rem',
          });
          return;
        }
        var tamanoArchivo = archivo.size / 1024 / 1024; // Tamaño en MB
        if (tamanoArchivo > 2) {
          Swal.fire({
              title: "Información de Servicio de Registros",
              text: "El archivo no debe exceder los 2 MB.",
              icon: "info",
              showConfirmButton: true,
              width: '30%',
              customClass: {
                  icon: 'small-swal-icon'
              },
              padding: '1.5rem',
          });
          return;
        }
        
        if (!tipoFormato) {
          Swal.fire({
              title: "Información de Servicio de Registros",
              text: "Faltó seleccionar el tipo de formato",
              icon: "info",
              showConfirmButton: true,
              width: '30%',
              customClass: {
                  icon: 'small-swal-icon' // Aplica una clase personalizada al ícono
              },
              padding: '1.5rem',
          });
          return;
        }

        // Crear el FormData a partir del formulario
        var formData = new FormData($("#data_formatos_escaneados")[0]);
        var token = $('#_token').val();

        // Mostrar modal de progreso y reiniciar valores
        $('#uploadModal').show();
        $('#progressBar').css('width', '0%').text('0%');
        $('#progressText').text("0%");

        // Llamada Ajax para subir el archivo
        $.ajax({
            url: '{{ route("subir_formatos_escaneados") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {'X-CSRF-TOKEN': token},
            // Manejo del progreso de la subida
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('#progressBar').css('width', percentComplete + '%').text(percentComplete + "%");
                        $('#progressText').text(percentComplete + "%");
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
              console.log(response.message);
              $('#archivo').val('');
              $('#tipo_formato').val(''); 
              obtenerFormatosSubidos();
            },
            error: function(response) {
                
            },
            complete: function() {
                // Ocultar el modal y reiniciar la barra de progreso al finalizar
                $('#uploadModal').hide();
                $('#progressBar').css('width', '0%').text('0%');
                $('#progressText').text("0%");
            }
        });
    });

    function obtenerFormatosSubidos() {
      var id_intervencion = {{$asegurado['id_intervencion']}}  // Obtener el valor de id_intervencion
      var token = $('#_token').val();  // Obtener el token CSRF

      console.log("ID de intervención: ", id_intervencion); // Verifica el valor

      if (!id_intervencion) {
          alert("El ID de intervención no se está recuperando correctamente.");
          return;
      }

      $.ajax({
          url: '{{ route("obtener_formatos_subidos") }}', // Ruta al controlador
          type: 'POST',
          data: {
              'id_intervencion': id_intervencion,  // Enviar id_intervencion
              '_token': token  // Enviar el token CSRF
          },
          headers: {
              'X-CSRF-TOKEN': token  // Asegúrate de enviar el token CSRF en los encabezados
          },
          success: function(response) {
            console.log(response);  // Verifica la respuesta del servidor
            if (response.success) {
             
                $('#lista_formatos_subidos').find("tbody").empty();
                response.data.forEach(function(documento) {
                    var nuevaFila = `
                        <tr>
                            <td>${documento.id}</td>
                            <td>${documento.tipo_formato ? documento.tipo_formato.descripcion : 'No disponible'}</td>
                            <td>
                                <button 
                                  class="btn btn-default btn-xs verPdfVisorFormatosEscaneados"
                                  id="verPdfVisorFormatosEscaneados"
                                  data-id="${documento.id}"
                                  data-nombre-archivo="${documento.nombre_archivo}"
                                  data-ruta="${documento.ruta}"
                                  data-id_tipo_formato=${documento.id_tipo_formato}
                                  data-tipo_documento=${documento.tipo_documento}
                                >
                                  <i class="fa fa-search"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#lista_formatos_subidos').find("tbody").append(nuevaFila);
                });
            } else {
                alert("Hubo un error al obtener los documentos.");
            }
          },
          error: function(xhr, status, error) {
              console.error("Error: ", error);  // Detalles del error
              alert("Error al obtener los documentos.");
          }
      });
    }
    function formatos() {
      $.ajax({
          url: '{{ route("lista_formatos") }}',
          type: 'GET',
          success: function(response) {
            if (response.success) {
               
                $('#lista_formatos').find("tbody").empty();
                response.data.forEach(function(documento) {
                    var nuevaFila = `
                        <tr>
                            <td>${documento.id}</td>
                            <td>${documento.nombre_archivo}</td>
                            <td>
                                <button class="btn btn-default btn-xs verPdfVisorFormatos" id="verPdfVisorFormatos" data-id="${documento.id}" data-nombre-archivo="${documento.nombre_archivo}"  data-ruta="${documento.ruta}" >
                                  <i class="fa fa-search"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#lista_formatos').find("tbody").append(nuevaFila);
                });
            } else {
                alert("Hubo un error al obtener los documentos.");
            }
          },
          error: function(xhr, status, error) {
              console.error("Error: ", error); 
              alert("Error al obtener los documentos.");
          }
      });
    }
  });
  $(document).on('click', '#verPdfVisorFormatosEscaneados', function (e) {
    Swal.fire({
      title: '<div class="titulo-padding"><p>Cargando...</p></div>',
      allowOutsideClick: false,
      showConfirmButton: false,
      customClass: {
          header : 'swal2-header-generar'
      },
      onBeforeOpen: () => {
        $('#blur-container').append('<div class="blur-background"></div>');
        Swal.showLoading();
        $('#contenedorVisorPdsFormatosEscaneados').html('');
        let html = '';
        const ruta = $(this).data('ruta');
        const nombre_archivo = $(this).data('nombre-archivo');
        const id_tipo_formato = $(this).data('id_tipo_formato');
        const tipo_documento = $(this).data('tipo_documento');
        
        html += `<object class="pdfView" type="${tipo_documento}" data="/formatos_escaneados/${nombre_archivo}"></object>`;

        $('#contenedorVisorPdsFormatosEscaneados').html(html);
        setTimeout(() => {
            Swal.hideLoading();
            Swal.fire({
                icon: 'success',
                title: 'Cargado correctamente!',
                showConfirmButton: false,
                timer: 1500,
                onClose: () => {
                    $('.blur-background').remove();
                }
            });
        }, 3000);
      }
    });
  });

  $(document).on('click', '.verPdfVisorFormatos', function () {
    Swal.fire({
      title: '<div class="titulo-padding"><p>Cargando...</p></div>',
      allowOutsideClick: false,
      showConfirmButton: false,
      customClass: {
          header : 'swal2-header-generar'
      },
      onBeforeOpen: () => {
        $('#blur-container').append('<div class="blur-background"></div>');
        Swal.showLoading();
        $('#contenedorVisorPdsFormatos').html('');
        let html = '';
        const ruta = $(this).data('ruta');
        const nombre_archivo = $(this).data('nombre-archivo');
        console.log(ruta);

        html += `<object class="pdfView" type="application/pdf" data="/${ruta}/${nombre_archivo}"></object>`;

        $('#contenedorVisorPdsFormatos').html(html);
        setTimeout(() => {
            Swal.hideLoading();
            Swal.fire({
                icon: 'success',
                title: 'Cargado correctamente!',
                showConfirmButton: false,
                timer: 1500,
                onClose: () => {
                    $('.blur-background').remove();
                }
            });
        }, 3000);
      }
    });
  });
</script>



<div class="row">
  <div class="panel with-nav-tabs panel-success">
      <div class="panel-heading">
          <ul class="nav nav-tabs">
              <li class="active"><a href="#tab1-historial" data-toggle="tab" style="font-size:12px">HISTORIAL DE FORMATOS SUBIDOS</a></li>
              <li><a href="#tab2-formatos" data-toggle="tab" style="font-size:12px">FORMATOS DE PACIENTES PARA IMPRIMIR</a></li>
          </ul>
      </div>
      <div class="panel-body">
          <div class="tab-content">
              <!-- Tab 1: Historial de Formatos Subidos -->
              <div class="tab-pane fade in active" id="tab1-historial">
                <form method="POST" id="data_formatos_escaneados" action="/subir_formatos_escaneados" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="id_asegurado" value="{{$asegurado['id_asegurado']}}">
                  <input type="hidden" name="id_intervencion" value="{{$asegurado['id_intervencion']}}">
                  <div class="form-horizontal">
                      <div class="form-group">
                          <div class="col-md-1">
                              <label for="">PACIENTE</label>
                          </div>
                          <div class="col-md-11">
                              <span id="paciente">{{$asegurado['asegurado_nombre']}} {{$asegurado['asegurado_paterno']}} {{$asegurado['asegurado_materno']}}</span>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="">TIPO DE FORMATO PARA EL PACIENTE:</label>
                              <select name="tipo_formato" id="tipo_formato" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($tipo_formatos as $formato)
                                    <option value="{{ $formato->id }}">{{ $formato->descripcion }}</option>
                                @endforeach
                            </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-6">
                              <input type="file" name="archivo" id="archivo" class="form-control" required>
                          </div>
                          <div class="col-md-6">
                              <button class="btn btn-primary btn-sm" id="btnSubirArchivo" type="button"><i class="fa fa-eye"></i> Subir Archivo PDF</button>
                          </div>
                      </div>
                  </div>
                </form>
                <!-- Historial de Archivos -->
                <div class="form-group">
                  <div class="col-md-12">
                      <table id="lista_formatos_subidos" class="table table-condensed table-bordered">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>TIPO DE FORMATO</th>
                              <th>ARCHIVO</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                      <label for="">VISOR DE PDFS</label>
                      <div id="contenedorVisorPdsFormatosEscaneados">
                      </div>
                  </div>
                </div>
              </div>
              
              <!-- Tab 2: Formatos para Imprimir -->
              <div class="tab-pane fade in" id="tab2-formatos">
                  <div class="form-horizontal">
                      <div class="form-group">
                          <div class="col-md-1">
                              <label for="">PACIENTE</label>
                          </div>
                          <div class="col-md-11">
                              <span>{{$asegurado['asegurado_nombre']}} {{$asegurado['asegurado_paterno']}} {{$asegurado['asegurado_materno']}}</span>
                          </div>
                      </div>
                      <table id="lista_formatos" class="table table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>ARCHIVO</th>
                            <th>OPCIÓN</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div class="form-group">
                        <div class="col-md-12">
                          <label for="">VISOR DE PDFS</label>
                            <div id="contenedorVisorPdsFormatos">
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
