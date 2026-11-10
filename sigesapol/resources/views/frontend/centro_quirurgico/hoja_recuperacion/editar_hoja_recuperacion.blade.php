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

<style>

  .table-sortable tbody tr {
  cursor: move;
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
  margin-left: -272%;
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

  .panel-heading2 {
  padding: 15px ;
  color: #3c763d;
  background-color: #e0e9e0;
  border-color: #e0e9e0;
  margin-left: 20px;
  }

  .titulo{
  margin-left: 75%;
  }

  .thead-dark {
  position: relative;
  left: 80px; 
  border: none;
  }

  #tblpaciente th{
    padding: 15px;
    text-align: center; 
    white-space: nowrap;
  }

  #tblpaciente td{
    text-align: center; 
  }

  .diagnostic-item1 {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      padding: 20px;
      border-bottom: 1px solid #ccc;
      width: 100%;
  }

  .diagnostic-item{
    margin-top: 6px;
  }

  .diagnostic-item1:last-child {
      border-bottom: none;
  }

  .btn-diagnost {
      display: flex;
      justify-content: space-around;
      margin-top: 20px;
      width: 100%;
  }

  .diagnostic-item1 input[type="checkbox"],
  .diagnostic-item input[type="checkbox"] {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid #808080; 
      background-color: white;
      cursor: pointer;
      position: relative;
  }

  .diagnostic-item1 input[type="checkbox"]:checked,
  .diagnostic-item input[type="checkbox"]:checked {
      background-color: #808080; 
      border-color: #808080;
  }

  .diagnostic-item1 input[type="checkbox"]:checked::before,
  .diagnostic-item input[type="checkbox"]:checked::before {
      content: "✔";
      position: absolute;
      top: -2px;
      left: 2px;
      font-size: 16px;
      color: white; 
  }

  .btn-funcionvital{
      display: flex;
      justify-content: space-around;
      margin-top: 28px;
      width: 100%;
      padding: 10px;
  }

  #table td{
      padding: 8px;
  }

</style>

<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
  }

  .seccion {
    display: none; /* Oculta las secciones por defecto */
    background-color: white;
    padding: 20px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .seccion-activa {
    display: block; /* Solo se muestra la sección activa */
  }

  button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
  }

  button:hover {
    background-color: #45a049;
  }

  .disabled-link {
    pointer-events: none;  /* Deshabilita los clics */
    color: grey;           /* Cambia el color para indicar deshabilitación */
    text-decoration: none; /* Elimina el subrayado */
  }
</style>



<script>
  document.getElementById('cama').addEventListener('input', function() {
    document.getElementById('cama2').value = this.value;
  });
  $(document).ready(function() {
    var procedimientosSeleccionados =[];
    var listIdDiagnostico =[];
    var listIdDiagnosticoObjetos =[];
    var lista_diagnosticos = {!! json_encode($diagnosticos) !!};
    getDiagnosticosByPrestacion();
    ariaPressed();

    $('#guardarSiguiente').click(function() {
      if ($('#form_editar')[0].checkValidity()) {
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
              /**********ENVIAR DATOS***************/
              let datos= {
                cama: $('#cama').val(),
                id_hoja_recuperacion: $('#id_hoja_recuperacion').val(),
                id_intervencion: $('#id_intervencion').val(),
                id_intraoperatorio: $('#id_intraoperatorio').val(),
                id_asegurado: $('#id_asegurado').val(),
                tiene_balance_hidrico: $('#tiene_balance_hidrico').val(),
                tiene_tratamiento: $('#tiene_tratamiento').val(),
                tiene_analisis: $('#tiene_analisis').val(),
                tiene_funciones_vitales: $('#tiene_funciones_vitales').val(),
                tiene_diagnostico_enfermeria: $('#tiene_diagnostico_enfermeria').val(),
                descripcion_tratamiento: $('#descripcion_tratamiento').val(),
                descripcion_analisis: $('#descripcion_analisis').val(),
                fecha_analisis: $('#fecha_analisis').val(),
                cvc_analisis: $('#cvc_analisis').val(),
                sf_analisis: $('#sf_analisis').val(),
                la_analisis: $('#la_analisis').val(),
                lpp_analisis: $('#lpp_analisis').val(),
                campo1_diagnostico_enfermeria: $('#campo1_diagnostico_enfermeria').val(),
                campo2_diagnostico_enfermeria: $('#campo2_diagnostico_enfermeria').val(),
                campo3_diagnostico_enfermeria: $('#campo3_diagnostico_enfermeria').val(),
                campo1_diagnostico_hidden: $('#campo1_diagnostico_hidden').val(),
                campo2_diagnostico_hidden: $('#campo2_diagnostico_hidden').val(),
                campo3_diagnostico_hidden: $('#campo3_diagnostico_hidden').val(),

                // nombre_procedimiento: $("input[name='nombre_procedimiento[]']").map(function() {
                //   return $(this).val();
                // }).get(),
                procedimiento_cantidad: $("input[name='procedimiento_cantidad[]']").map(function() {
                    return $(this).val();
                }).get(),
                miSelect: $("select[name='miSelect[]']").map(function() {
                    return $(this).val();
                }).get(),
                id_procedimiento: $("input[name='id_procedimiento[]']").map(function() {
                  return $(this).val();
                }).get(),
                accionRowProcedimiento: $("input[name='accionRowProcedimiento[]']").map(function() {
                  return $(this).val();
                }).get(),
                id_diagnostico_hidden: $("input[name='id_diagnostico_hidden[]']").map(function() {
                  return $(this).val();
                }).get(),
                id_prestacion: <?php echo $datos_intervencion->id_prestacion; ?>
              };
              $.ajax({
                  url: '{{ url('update_hoja_recuperacion') }}', 
                  method: 'POST', 
                  data: datos,
                  dataType: 'json',
                  success: function(response) {
                      Swal.close();
                      if(response.success){
                        Swal.fire({
                          icon: 'success',
                          title: 'Éxito',
                          text: 'Los datos han sido actualizados correctamente.',
                          timer: 2000,
                          showConfirmButton: false
                        });
                        setTimeout(function() {
                          $('#modal-editar-hojarecuperacion').modal('hide'); 
                        }, 2000);
                      }else{
                        Swal.fire({
                            title: 'Campos requeridos',
                            html: 'Ingrese los campos requeridos', 
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                      }
                  },
                  error: function(xhr) {
                      Swal.fire({
                          title: 'Error',
                          text: 'Ocurrió un error inesperado. Inténtalo de nuevo.',
                          icon: 'error',
                          confirmButtonText: 'Aceptar'
                      }); 
                  }
              });
          }
        });
      } else {
        // Si hay errores, muestra el mensaje de validación del navegador
        $('#form_editar')[0].reportValidity();
      }
    });

    $('#addProcedimientoHR').on('click',function(){
      addProcedimientoHR();
      var id_prestacion = <?php echo $datos_intervencion->id_prestacion; ?>;
      agregarDiagnosticosAprocedimientos(id_prestacion);
    });
    function addProcedimientoHR() {
      var newRow = "";
      var ind = $('#tblProcedimientoHR tbody tr').length;

      if (ind > 0) {
        $('.id_procedimiento').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblProcedimientoHR tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow += '<td style="width:60%;"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" id="nombre_procedimiento_hr' +
        ind + '" class="form-control" style="height: 28px !important;font-weight: 800;" />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query procedimiento" id="id_procedimiento_hr' + ind +
        '" name="id_procedimiento[]"  data-id='+ind+'>';
      newRow += '<input type="hidden" class="form-control typeahead tt-query "  name="accionRowProcedimiento[]" value="nuevo"></td>';
      newRow +=
        '<td style="width:15%;"><input type="number" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
        ind + '" name="procedimiento_cantidad[]" value="1" required></td>';
      newRow += '<td><select class="form-control select-diagnostico-hr"  id="miSelectHR' + ind + '" name="miSelect[]"></select></td>';
      newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimientoHR tbody').append(newRow);

      

      const selectElement = document.getElementById('miSelectHR' + ind);
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
      $("#nombre_procedimiento_hr" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
          console.log(request);
          
          $.ajax({
            url: '../obtener_procedimiento_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
            dataType: "json",
            success: function(data) {
              let listaProcedimientoSelecionado = $(".procedimiento").map(function() {return parseInt($(this).val()) || null;}).get().filter(id => id !== null);
              let filtro = data.filter((element) => {
                const enUso = listaProcedimientoSelecionado.includes(parseInt(element.codigo));
                return !enUso;
              });
              response(filtro);
            }
          });
        },
        select: function(e, ui) {
          var id_procedimiento = ui.item.id;
          $("#id_procedimiento_hr" + ind).val(id_procedimiento);
          $("#nombre_procedimiento_hr" + ind).prop("readonly", true);
        }
      }); 
    }
    function agregarDiagnosticosAprocedimientos(id_prestacion) {  
      var lista_diagnosticos = <?php echo json_encode($diagnosticos);?>;
      
      var cuerpo_DIAGNOSTICOS_PRESTACION = '' ;
      $.each(lista_diagnosticos, function (key, value) { 
        cuerpo_DIAGNOSTICOS_PRESTACION += '<option value="' + value.id_diagnostico + '">' + value.nombre + '</option>';
      }); 
      $(".select-diagnostico-hr").html(cuerpo_DIAGNOSTICOS_PRESTACION);
    }
    $('#tblProcedimientoHR tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      var fila = obj.closest('tr');
      var filaPosition = fila.index();
      obj.parent().parent().remove();
    });

    function getDiagnosticosByIndex(ind) {
      var select = document.getElementById('miSelectHR' + ind);
      if (!select || !lista_diagnosticos) return;

      const selectedId = parseInt(select.getAttribute('data-id-diagnostico'));
      select.innerHTML = "";

      $.each(lista_diagnosticos, function (key, value) {
        let option = document.createElement('option');
        option.value = value.id_diagnostico;
        option.textContent = value.codigo + ' - ' + value.nombre;

        if (value.id_diagnostico === selectedId) {
          option.selected = true;
        }

        select.appendChild(option);
      });
    }

    @foreach ($procedimientos_hoja_recuperacion as $key => $phr)
      getDiagnosticosByIndex({{ $key }});
    @endforeach

    function toggleFieldset(buttonId, fieldsetId) {
      var ariaPressed = $(buttonId).attr("aria-pressed");
      $(fieldsetId).toggle(ariaPressed === "false");
    }

    $('#tratamiento').on('click', function () {
      toggleFieldset('#tratamiento', '#fsatratamiento');
      var tratamiento = $("#tratamiento").attr("aria-pressed");
      if(tratamiento=="false"){
        $('#tiene_tratamiento').val(true);
      }else{
        $('#tiene_tratamiento').val(false);
      }
    });

    $('#analisis').on('click', function () {
      toggleFieldset('#analisis', '#fsanalisis');
      var analisis = $("#analisis").attr("aria-pressed");
      if(analisis=="false"){
        $('#tiene_analisis').val(true);
      }else{
        $('#tiene_analisis').val(false);
      }
    });


    $('#diagnostico-enfermeria').on('click', function () {
      toggleFieldset('#diagnostico-enfermeria', '#fsdiagnostico-enfermeria');
      var diagnostico_enfermeria = $("#diagnostico-enfermeria").attr("aria-pressed");
      if(diagnostico_enfermeria=="false"){
        $('#tiene_diagnostico_enfermeria').val(true);
      }else{
        $('#tiene_diagnostico_enfermeria').val(false);
      }
    });

    $('#modal-editar-hojarecuperacion').modal({
        backdrop: 'static',
        keyboard: false
    });

    function getDiagnosticosByPrestacion(){
      var lista_diagnosticos = <?php echo json_encode($diagnosticos);?>;
      var cuerpo_DIAGNOSTICOS_PRESTACION = '' ;
      /***************DATOS PARA LA TABLA***********/
        $("#diagnosticos_io_hr").empty();
        $.each(lista_diagnosticos, function (key, value) { 
          cuerpo_DIAGNOSTICOS_PRESTACION = cuerpo_DIAGNOSTICOS_PRESTACION+
              '<tr>'+ 
                '<td>'+value.codigo+'</td>'+
                '<td>'+value.nombre+'</td>'+
                '<td>'+value.tipo+'</td>'+
                '<td>'+value.clasificacion+'</td>'+
              '</tr>';
        }); 
        $('#diagnosticos_io_hr').append(cuerpo_DIAGNOSTICOS_PRESTACION);
    }

    $(document).on("click", ".EditarFilaProcedimiento", function() {
      let row = $(this).closest("tr");
      row.find("input, select").prop("disabled", false);
      row.find("input[name='accionRowProcedimiento[]']").val("edit");
      row.find("input[type='hidden'][name='procedimiento_cantida_editar[]']").remove();
      row.find("input[type='hidden'][name='miSelect_editar[]']").remove();
      $(this).closest('tr').css('background-color', '#87c787');
    });

    $('.AnularFilaProcedimiento').on('click', function() {
      let row = $(this).closest("tr");
      row.find("input:not(.id_procedimiento_hidden), select").prop("disabled", true);
      row.find("input[name='accionRowProcedimiento[]']").val("delete");
      row.find("input[type='hidden'][name='procedimiento_cantida_editar[]']").remove();
      row.find("input[type='hidden'][name='miSelect_editar[]']").remove();
      row.css('background-color', '#f8d7da');
    });

    $('#restaurarColoresProcedimientos').on('click', function() {
      $("input[name='accionRowProcedimiento[]']").val("");
      $("#tblProcedimientoHR .procedimentos_registrados").find("input[type='text'], input[type='number']").prop("disabled", true);
      $("#tblProcedimientoHR .procedimentos_registrados").find("select").prop("disabled", true);
      $('#tblProcedimientoHR tr').each(function () {
        const tdCamposHidden = $(this).find('div.campos_hidden');
        // Verifica si ya existen los inputs antes de agregar nuevos
        if (tdCamposHidden.length > 0 && tdCamposHidden.find("input").length === 0) {
          tdCamposHidden.append(`<input type="hidden" name="accionRowProcedimiento[]" value="">`);
          tdCamposHidden.append(`<input type="hidden" name="procedimiento_cantida_editar[]" value="">`);
          tdCamposHidden.append(`<input type="hidden" name="miSelect_editar[]" value="">`);
        }
      });
      $('#tblProcedimientoHR tr').css('background-color', '');
    });
      // Deshabilita todos los selects en la tabla
      // $("#tblProcedimientoHR").find(".procedimiento_cantidad").prop("disabled", true);
      // $("#tblProcedimientoHR").find(".select-diagnostico").prop("disabled", true);
      // $('#tblProcedimientoHR tr').css('background-color', '');
   

    function ariaPressed() {
      var tiene_tratamiento = {{ $hoja_recuperacion->tiene_tratamiento ? 'true' : 'false' }};
      console.log('tiene_tratamiento:'+tiene_tratamiento);
      
      if (tiene_tratamiento) {
        $('#tiene_tratamiento').val(true);
        $('#tratamiento').attr("aria-pressed", "true");
        $('#tratamiento').addClass("active");
        $("#fsatratamiento").show();
      }

      var tiene_analisis = {{ $hoja_recuperacion->tiene_analisis ? 'true' : 'false' }};
      if (tiene_analisis) {
        $('#tiene_analisis').val(true);
        $('#analisis').attr("aria-pressed", "true");
        $('#analisis').addClass("active");
        $("#fsanalisis").show();
      }

      var tiene_diagnostico_enfermeria = {{ $hoja_recuperacion->tiene_diagnostico_enfermeria ? 'true' : 'false' }};
      if (tiene_diagnostico_enfermeria) {
         $('#tiene_diagnostico_enfermeria').val(true);
        $('#diagnostico-enfermeria').attr("aria-pressed", "true");
        $('#diagnostico-enfermeria').addClass("active");
        $("#fsdiagnostico-enfermeria").show();
      }
    }

    function sincronizarCheckbox(idCheckbox, idHidden) {
      var checkbox = document.getElementById(idCheckbox);
      var hidden = document.getElementById(idHidden);

      if (!checkbox || !hidden) return;

      hidden.value = checkbox.checked ? 1 : 0;
      checkbox.onchange = function () {
        hidden.value = checkbox.checked ? 1 : 0;
      };
    }
    sincronizarCheckbox('campo1_diagnostico_enfermeria', 'campo1_diagnostico_hidden');
    sincronizarCheckbox('campo2_diagnostico_enfermeria', 'campo2_diagnostico_hidden');
    sincronizarCheckbox('campo3_diagnostico_enfermeria', 'campo3_diagnostico_hidden');

    $('.select-diagnostico-hr').each(function (index) {
      const valorSeleccionado = $(this).val(); // esto funciona aunque esté disabled
      console.log('Valor seleccionado:', valorSeleccionado);

      $('.id_diagnostico_hidden').eq(index).val(valorSeleccionado ?? '');
    });

  });
</script>



  <div id="seccion1_editar" class="seccion seccion-activa">
    
      <form name="form_editar" id="form_editar" role="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="id_hoja_recuperacion" name="id_hoja_recuperacion" value="{{ $hoja_recuperacion->id }}">
        <input type="hidden" id="id_intraoperatorio" name="id_intraoperatorio" value="{{ isset($intraoperatorio->id)?$intraoperatorio->id:'' }}">
        <input type="hidden"id="id_asegurado"  name="id_asegurado" value="{{ $datos_intervencion->id_asegurado }}">
        <input type="hidden" id="tiene_balance_hidrico" name="tiene_balance_hidrico" value="false">
        <input type="hidden" id="tiene_tratamiento" name="tiene_tratamiento" value="false">
        <input type="hidden" id="tiene_funciones_vitales" name="tiene_funciones_vitales" value="false">
        <input type="hidden" id="tiene_diagnostico_enfermeria" name="tiene_diagnostico_enfermeria" value="false">
        <input type="hidden" name="id_prestacion" value="{{$datos_intervencion->id_prestacion}}">
        <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
        <input type="hidden" id="tiene_analisis" name="tiene_analisis" value="false">
        <input type="hidden"id="id_intervencion"  name="id_intervencion" value="{{ $datos_intervencion->id }}">
        

        <div class="panel panel-info contenedor-datos-generales">
          <div class="panel-body">
            <div class="row intro-contenedor">
              <div class="col-lg-4">
                <label>NOMBRE DEL PACIENTE :</label>
                <input type="text" readonly class="form-control" value="{{ $datos_paciente_intervencion['paciente']}}">
              </div>
              <div class="col-lg-2">
                <label for="tipo"><b>EDAD :</b></label>
                <input type="text" readonly class="form-control" value="{{ $datos_paciente_intervencion['edad_corta']}} años">
              </div>
              <div class="col-lg-3">
                <label class="control-label">CAMA :</label>
                <input type="text" id="cama" name="cama" value="{{ $hoja_recuperacion->cama }}" class="form-control" >
              </div>
              <div class="col-lg-3">
                <label>SALA</label>
                <input type="text" readonly class="form-control datepicker" value="{{ $datos_paciente_intervencion['sala']}}">
              </div>
            </div>
            <br>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>DIAGNÓSTICO OPERATORIO</b>
                  </div>
                </div>
                <div class="panel-body">
                  <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                      <tr>
                        <th width="20%">Codigo</th>
                        <th width="60%">Nombre</th>
                        <th width="20%">Tipo</th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                        <?php foreach($diagnostico_operatorio as $row):
                        ?>
                        <tr class="row-diagnosticosfinal">
                          <td>
                            <input type="text"  id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                          </td>
                          <td>
                            <input type="text" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                          </td>
                          <td>
                            <input type="text" value="{{ $row->descripcion }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                        </td>
                        </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </fieldset>
            <div class="row intro-contenedor">
              <div class="col-lg-6">
                <label>ANTECEDENTE PERSONAL :</label>
                <input type="text" readonly class="form-control" value="{{$datos_paciente_intervencion['antecedente_personal']}}">
              </div>
              <div class="col-lg-6">
                <label>ANTECEDENTE FAMILIAR :</label>
                <input type="text" readonly class="form-control" value="{{$datos_paciente_intervencion['antecedente_familiar']}}">
              </div>
            </div>
            <br>
            <fieldset>
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center" style="position: relative">
                    <b>TIPOS DE ANESTESIA</b>
                  </div>
                </div>
                <div class="panel-body">
                  <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                      <tr>
                        <th width="20%">Descripción</th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                        <?php foreach($tipos_anestesia_aq as $row):
                        ?>
                        <tr class="row-diagnosticosfinal">
                          <td>
                            <input type="text"  id="" value="{{ $row->descripcion }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                          </td>
                        </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </fieldset>
            <div class="row intro-contenedor">
              <div class="col-lg-6">
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative">
                        <b>CIRUJANOS</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="60%">Nombres</th>
                            <th width="40%">Tipo</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                            <?php foreach($cirujanos as $row):
                            ?>
                            <tr class="row-diagnosticosfinal">
                              <td>
                                <input type="text"   value="{{ $row->medico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                              </td>
                              <td>
                                <input type="text"  value="{{ $row->tipo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                              </td>
                            </tr>
                          <?php endforeach;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center" style="position: relative">
                        <b>ANESTESIOLOGOS</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                        <thead>
                          <tr>
                            <th width="60%">Nombres</th>
                            <th width="40%">Tipo</th>
                          </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                            <?php foreach($anestesiologos as $row):
                            ?>
                            <tr class="row-diagnosticosfinal">
                              <td>
                                <input type="text"   value="{{ $row->medico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                              </td>
                              <td>
                                <input type="text"  value="{{ $row->tipo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                              </td>
                            </tr>
                          <?php endforeach;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-info contenedor-datos-generales">
          <div class="panel-body">
            <div class="row intro-contenedor">
              <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                <label>TRATAMIENTO</label>
              </div>
              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                <button tabindex="70" type="button" id="tratamiento" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                <div class="handle"></div>
                </button>
              </div>
            </div>
          </div>
          <fieldset id="fsatratamiento" style="display:none">
            <div class="d-flex">
              <div class="col-md-12">
                <label style="margin-left: 20px;">DESCRIPCION:</label>
                <textarea  name="descripcion_tratamiento" id="descripcion_tratamiento" cols="50" rows="7" class="form-control">{{ isset($hoja_recuperacion->descripcion_tratamiento)?$hoja_recuperacion->descripcion_tratamiento:'' }}</textarea>
              </div>
            </div>
          </fieldset>
        </div>

        <div class="panel panel-info contenedor-datos-generales">
          <div class="panel-body">
            <div class="row intro-contenedor">
              <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                <label>ANALISIS</label>
              </div>
              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                <button tabindex="70" type="button" id="analisis" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                <div class="handle"></div>
                </button>
              </div>
            </div>
          </div>
          <fieldset id="fsanalisis" style="display:none">
            <div class="d-flex">
              <div class="col-md-6">
                <textarea  name="descripcion_analisis" id="descripcion_analisis" cols="50" rows="7" class="form-control">{{ isset($hoja_recuperacion->descripcion_analisis)?$hoja_recuperacion->descripcion_analisis:'' }}</textarea>
              </div>
              <div class="col-md-6">
              <table class="table " cellspacing="0" width="100%" cellspacing="0" width="100%">
                <tr>
                  <td>FECHA:</td>
                  <td><input type="date" class="form-control" id="fecha_analisis" name="fecha_analisis" value="<?php echo isset($hoja_recuperacion->fecha_analisis) ? date('Y-m-d', strtotime($hoja_recuperacion->fecha_analisis)) : ''; ?>"></td>
                </tr>
                <tr>
                  <td>CVC:</td>
                  <td><input type="text" class="form-control" id="cvc_analisis" name="cvc_analisis"  value="{{ isset($hoja_recuperacion->cvc_analisis)?$hoja_recuperacion->cvc_analisis:'' }}"></td>
                </tr>
                <tr>
                  <td>SF:</td>
                  <td><input type="text" class="form-control" id="sf_analisis" name="sf_analisis" value="{{ isset($hoja_recuperacion->sp_analisis)?$hoja_recuperacion->sp_analisis:'' }}"></td>
                </tr>
                <tr>
                  <td>L.A:</td>
                  <td><input type="text" class="form-control" id="la_analisis" name="la_analisis" value="{{ isset($hoja_recuperacion->la_analisis)?$hoja_recuperacion->la_analisis:'' }}"></td>
                </tr>
                <tr>
                  <td>LPP:</td>
                  <td><input type="text" class="form-control" id="lpp_analisis" name="lpp_analisis" value="{{ isset($hoja_recuperacion->lpp_analisis)?$hoja_recuperacion->lpp_analisis:'' }}"></td>
                </tr>
                
              </table>
              </div>
            </div>
          </fieldset>
        </div>

        <div class="panel panel-info contenedor-datos-generales">
          <div class="panel-body">
              <div class="form-group">
                <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                  <label>DIAGNOSTICO DE ENFERMERIA</label>
                </div>
                <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                    <button tabindex="70" type="button" id="diagnostico-enfermeria" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                    <div class="handle"></div>
                    </button>
                </div>
              </div>
          </div>  

          <fieldset id="fsdiagnostico-enfermeria" style="display:none">
            <div class="row">
              <div class="diagnostic-item1">
                  1.- PATRÓN RESPIRATORIO INEFICAZ RELACIONADO CON DOLOR QUE SE EVIDENCIA POR DISNEA
                  <input type="hidden" name="campo1_diagnostico_hidden" id="campo1_diagnostico_hidden" value="0">
                  <input type="checkbox" id="campo1_diagnostico_enfermeria" name="campo1_diagnostico_enfermeria"  value="1" {{ $hoja_recuperacion->campo1_diagnostico_enfermeria ? 'checked' : '' }}>
              </div>
              <div class="diagnostic-item1">
                  2.- LIMPIEZA INEFICAZ DE LAS VÍAS AÉREAS RELACIONADO CON LA RETENCIÓN DE LAS SECRECIONES SECUNDARIO A<br>
                  ESTADO DE INCONSCIENCIA
                  <input type="hidden" name="campo2_diagnostico_hidden" id="campo2_diagnostico_hidden" value="0">
                  <input type="checkbox" id="campo2_diagnostico_enfermeria" name="campo2_diagnostico_enfermeria" value="1" {{ $hoja_recuperacion->campo2_diagnostico_enfermeria ? 'checked' : '' }}>
              </div>
              <div class="diagnostic-item1">
                  3.- TERMORREGULACIÓN INEFICAZ RELACIONADO CON TEMPERATURA DEL AMBIENTE LUCTUANTE
                  <input type="hidden" name="campo3_diagnostico_hidden" id="campo3_diagnostico_hidden" value="0">
                  <input type="checkbox" id="campo3_diagnostico_enfermeria" name="campo3_diagnostico_enfermeria" value="1" {{ $hoja_recuperacion->campo3_diagnostico_enfermeria ? 'checked' : '' }}>
              </div>
            </div>
          </fieldset>
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
          <th width="15%">Clasificación</th>
          </tr>
          </thead>
          <tfoot>
          </tfoot>
          <tbody id="diagnosticos_io_hr">
          
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
                <div class="col-lg-3" style="padding-bottom: 1rem;">
                    <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addProcedimientoHR">Agregar Procedimiento</button>
                    <span class="invalid-feedback" style="display: none; color: red;">
                      {{ $errors->first('addProcedimientoHR') }}
                    </span>
                </div>
                <div class="col-lg-12">
                  <table class="table table-bordered" id="tblProcedimientoHR" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                      <tr>
                        <th width="45%">Procedimiento</th>
                        <th width="5%">Cantidad</th>
                        <th width="45%">Diagnosticos</th>
                        <th width="10%" colspan="2">
                          <div style="width: 100%; display: flex; justify-content: space-around">
                            <span>Cancelar Acción</span>
                            <button type="button" class="btn btn-danger btn-xs" style="width: auto;" id="restaurarColoresProcedimientos">
                              <i class="fa fa-times"></i>
                            </button>
                          </div>  
                        </th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                      @foreach ($procedimientos_hoja_recuperacion as $key => $phr)
                        <tr class="procedimentos_registrados">
                          <td>
                            <input type="text"
                                  class="form-control procedimiento"
                                  value="{{ $phr->codigo }} - {{ $phr->descripcion }}"
                                  style="height: 28px !important; font-weight: 800;"
                                  disabled />
                            <input type="hidden" class="id_procedimiento_hidden"  name="id_procedimiento[]"  data-id="{{$key}}"  value="{{ $phr->id }}">
                            <div class="campos_hidden"> 
                                <input type="hidden" value="" class="accionRowProcedimiento" id="accionRowProcedimiento" name="accionRowProcedimiento[]"/>
                                <input type="hidden" value=""  id="procedimiento_cantida_editar" name="procedimiento_cantida_editar[]"/>
                                <input type="hidden" value=""  id="miSelect_editar" name="miSelect_editar[]"/>
                            </div>
                          </td>
                          <td>
                            <input type="number"
                                  maxlength="2"
                                  placeholder="Cantidad"
                                  class="form-control procedimiento_cantidad typeahead tt-query"
                                  style="margin:0 20px;width:150px"
                                  id="procedimiento_cantida{{ $key }}"
                                  name="procedimiento_cantidad[]"
                                  value="{{ $phr->cantidad }}"
                                  disabled
                                  required />
                          </td>
                          <td>
                            <input type="hidden" class="id_diagnostico_hidden"  name="id_diagnostico_hidden[]">
                            <select class="form-control select-diagnostico-hr"
                                    id="miSelectHR{{ $key }}"
                                    data-id-diagnostico="{{ $phr->id_diagnosticos }}"
                                    name="miSelect[]" disabled></select>
                          </td>
                           <td>
                            <button type="button"  class="btn btn-primary btn-xs EditarFilaProcedimiento" data-id-procedimiento="{{ $phr->id }}" data-toggle="modal" style="margin-left:4px">
                              <i class="fa fa-edit"></i> Editar
                            </button>
                          </td>
                          <td>
                            
                            <button type="button"  class="btn btn-danger AnularFilaProcedimiento btn-xs" data-id-procedimiento="{{ $phr->id }}" data-toggle="modal" style="margin-left:4px">
                              <i class="fa fa-times"></i> Eliminar
                            </button>  
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      <button type="button" id="guardarSiguiente">Guardar datos</button>
   
  </div>

