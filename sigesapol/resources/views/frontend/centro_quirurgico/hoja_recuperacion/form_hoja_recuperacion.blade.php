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
   
    function cargarDatosGlobalesHojaR(id_asegurado){
      $('#id_asegurado_bh').val(id_asegurado);
      var var_url='{{url('hoja_recuperacion_datos_globales_balance')}}';
      $.ajax({
        url:var_url,
        type:'POST',
        dataType:'JSON',
        data: {
          id_asegurado: id_asegurado
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
          $('#tipo_sangre_bh_nuevo').html(data.datos_paciente.tipo_sangre);
          
          /*****************TURNOS**********/;
          $("#turnos_ver").empty(); // Vaciar antes de añadir nuevos turnos
          $.each(data.turnos, function (key, value) { 
            $("#turnos_ver").append("<option value='" + value.id + "'>" +value.descripcion + "</option>");
          }); 
          
          /***********CARACTER FISICO*********/
          let html_caracter_fisico = '' ;

          html_caracter_fisico +='<div class="row">';
          html_caracter_fisico +='<div class="col-md-6">';
          html_caracter_fisico +='<table class="table table-striped table-list-search" style="margin-bottom: 0px !important;">';
          html_caracter_fisico +='<td>';
          html_caracter_fisico +='PESO: <input type="number" id="peso_bh" class="form-control" name="peso_kg"> KG';
          html_caracter_fisico +='</td>';
          html_caracter_fisico +='</table>';
          html_caracter_fisico +='</div>';
          html_caracter_fisico +='<div class="col-md-6"></div>';
          html_caracter_fisico +='</div>';

          $("#balance_hidrico").empty();
          html_caracter_fisico +='<div class="row">';
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
                  html_caracter_fisico +='<input type="hidden"  name="es_visible[]" value="0">';
                  html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_id_ingreso[]">';
                  html_caracter_fisico +='<input type="hidden" name="id_caracter_fisico_ingreso[]" value="'+value.id+'">';
                  html_caracter_fisico +='<input type="hidden" name="descripcion_caracter_fisico_ingreso[]">'+value.descripcion;
                  html_caracter_fisico +='</td>';
                  html_caracter_fisico +='<td colspan="2"><input type="hidden" name="valor_tabla_ingreso[]">';
                  html_caracter_fisico +='<input type="number" step="1"   class="form-control" id="INGRESO_AO" disabled>';
                  html_caracter_fisico +='<input type="hidden" name="valor_ingreso[]" id="INGRESO_AO2">';
                  html_caracter_fisico +='</td>';
                  html_caracter_fisico +='</tr>';
                }
                if ([3].includes(value.secuencia)) {
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
                  html_caracter_fisico +='<input type="number" step="1"   class="form-control "  id="INGRESO_PI" disabled>';
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

          html_caracter_fisico +='</div>';
          $('#balance_hidrico').append(html_caracter_fisico);
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
            actualizarTotalIngreso();
            actualizarTotalEgreso();
          });
          $('input, select').not(this).prop('disabled', true);
          $('#link').addClass('disabled-link').attr('tabindex', -1);  
          $('#peso_bh').not(this).prop('disabled', false);

          $('#peso_bh, #hora_registro, #INGRESO_AO, #INGRESO_PI').on('input', function() {
            calculo_horas_peso('peso_bh', 'hora_registro', 'INGRESO_AO', 'INGRESO_PI');
            calculo_horas_peso('peso_bh', 'hora_registro', 'INGRESO_AO2', 'INGRESO_PI2');
            actualizarTotalIngreso();

            if ($(this).val().trim() !== '') {
              // Habilitar todos los campos nuevamente
              $('input, select').prop('disabled', false);
              $('#link').removeClass('disabled-link').removeAttr('tabindex');  // Reactivar enlace
            } else {
              $('input, select').not(this).prop('disabled', true);
              $('#link').addClass('disabled-link').attr('tabindex', -1);  // Simular enlace deshabilitado
            }
          });
        }

      });
    } 

    $('#guardarSiguiente').click(function() {
      if ($('#form1')[0].checkValidity()) {
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

                nombre_procedimiento: $("input[name='nombre_procedimiento[]']").map(function() {
                  return $(this).val();
                }).get(),
                procedimiento_cantidad: $("input[name='procedimiento_cantidad[]']").map(function() {
                    return $(this).val();
                }).get(),
                miSelect: $("select[name='miSelect[]']").map(function() {
                    return $(this).val();
                }).get(),
                id_procedimiento_hr_form: $("input[name='id_procedimiento_hr_form[]']").map(function() {
                  return $(this).val();
                }).get(),
                id_prestacion: <?php echo $asegurado['id_prestacion']; ?>
              };
              $.ajax({
                  url: '{{ url('guardar_hoja_recuperacion') }}', 
                  method: 'POST', 
                  data: datos,
                  dataType: 'json',
                  success: function(response) {
                      Swal.close(); // Cierra el loading
                      if(response.success){
                        $('#id_hoja_recuperacion_balance').val(response.id_hoja_recuperacion);
                        $('#id_hoja_recuperacion_vital').val(response.id_hoja_recuperacion);
                        $('#seccion1').removeClass('seccion-activa');
                        $('#seccion2').addClass('seccion-activa');
                      }else{
                        validaCamposHR(response);
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
        $('#form1')[0].reportValidity();
      }
    });

    $('#crearBalanceHidrico').click(function () {
    
      $("#registrarBalanceHidrico").attr("id","registrarBalanceHidrico");
      $("#registrarBalanceHidrico").attr("disabled",false);
      $('#FormularioBalanceHidrico').trigger("reset");
      $('#tituloFormularioBalanceHidrico').html("Registro de Balance Hídrico");
      $('#modalBalanceHidrico').modal('show'); 
      $("table[id^='tbl_tabla_'] tbody").empty();
      
      var id_asegurado = $('#id_asegurado_bh').val();
     
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
      cargarDatosGlobalesHojaR({{ $asegurado['id_asegurado'] }});
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

    $('#addProcedimientoHRForm').on('click',function(){
      addProcedimientoHRForm();
      var id_prestacion = <?php echo $asegurado['id_prestacion']; ?>;
      agregarDiagnosticosAprocedimientos(id_prestacion);
    });
    function addProcedimientoHRForm() {
      var newRow = "";
      var ind = $('#tblProcedimientoHRForm tbody tr').length;

      if (ind > 0) {
        $('.id_procedimiento').each(function() {
          var ind_tmp = $(this).data("id");
          if (ind_tmp => ind) {
            ind = Number(ind_tmp) + 1;
          }
        });
              
        fila_registros = $('#tblProcedimientoHRForm tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_registro = fila_registros[idval];
      }

      newRow += '<tr>';
      newRow += '<td style="width:60%;"><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" id="nombre_procedimiento_hr_form' +
        ind + '" class="form-control" style="height: 28px !important;font-weight: 800;" />';
      newRow += '<input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento_hr_form' + ind +
        '" name="id_procedimiento_hr_form[]"  data-id='+ind+'></td>';
      newRow +=
        '<td style="width:15%;"><input type="number" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
        ind + '" name="procedimiento_cantidad[]" value="1" required></td>';
      newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
      newRow +='<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
      newRow += '</tr>';
      $('#tblProcedimientoHRForm tbody').append(newRow);

      

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
      $("#nombre_procedimiento_hr_form" + ind).autocomplete({
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
          $("#id_procedimiento_hr_form" + ind).val(id_procedimiento);
          $("#nombre_procedimiento_hr_form" + ind).prop("readonly", true);
        }
      }); 
    }
    function agregarDiagnosticosAprocedimientos(id_prestacion) {  
      var lista_diagnosticos = <?php echo json_encode($diagnosticos);?>;
      var cuerpo_DIAGNOSTICOS_PRESTACION = '' ;
      $.each(lista_diagnosticos, function (key, value) { 
        cuerpo_DIAGNOSTICOS_PRESTACION += '<option value="' + value.id_diagnostico + '">' + value.nombre + '</option>';
      }); 
      $(".select-diagnostico").html(cuerpo_DIAGNOSTICOS_PRESTACION);
    }
    $('#tblProcedimientoHR tbody').on('click', 'button.deleteFila', function() {
      var obj = $(this);
      var fila = obj.closest('tr');
      var filaPosition = fila.index();
      obj.parent().parent().remove();
    });
    function validaCamposHR(response){
      $('#cama').removeClass('is-invalid');
      $('#campo1_diagnostico_enfermeria').removeClass('is-invalid');
      $('#campo2_diagnostico_enfermeria').removeClass('is-invalid');
      $('#campo3_diagnostico_enfermeria').removeClass('is-invalid');
      $('input, textarea, [type="date"]').removeClass('is-invalid');
      $('.invalid-feedback').hide();
      $.each(response.errors, function(field, error) {
        var $inputField = $('#' + field);
        if ($inputField.length) {
          $inputField.addClass('is-invalid');
          $inputField.next('.invalid-feedback').text(error[0]).show();
        }
     });
      const tiene_diagnostico_enfermeria = $('#tiene_diagnostico_enfermeria').val(); // Obtiene el valor del input oculto
      const campo1Diagnostico = $('#campo1_diagnostico_enfermeria'); // Selección del campo 1 por ID
      const campo2Diagnostico = $('#campo2_diagnostico_enfermeria'); // Selección del campo 2 por ID
      const campo3Diagnostico = $('#campo3_diagnostico_enfermeria'); // Selección del campo 3 por ID

      // Validar campo 1
      if (tiene_diagnostico_enfermeria === "true" && !campo1Diagnostico.is(':checked')) {
          campo1Diagnostico.addClass('is-invalid');
      } else {
          campo1Diagnostico.removeClass('is-invalid');
      }
      // Validar campo 2
      if (tiene_diagnostico_enfermeria === "true" && !campo2Diagnostico.is(':checked')) {
          campo2Diagnostico.addClass('is-invalid');
      } else {
          campo2Diagnostico.removeClass('is-invalid');
      }
      // Validar campo 3
      if (tiene_diagnostico_enfermeria === "true" && !campo3Diagnostico.is(':checked')) {
          campo3Diagnostico.addClass('is-invalid');
      } else {
          campo3Diagnostico.removeClass('is-invalid');
      }

      let cantidadProcedimiento = 0;
     
      //PROCEDIMIENTO
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
        $('#addProcedimientoHR').addClass('is-invalid');
      }else{
        $('#addProcedimientoHR').removeClass('is-invalid');
      } 
    }

  });
</script>



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

  $(document).ready(function () {
    getDiagnosticosByPrestacion();

    function toggleFieldset(buttonId, fieldsetId) {
      var ariaPressed = $(buttonId).attr("aria-pressed");
      $(fieldsetId).toggle(ariaPressed === "false");
    }

    $('#addBalanceHidrico').on('click', function () {
      toggleFieldset('#addBalanceHidrico', '#fsaddBalanceHidrico');
      var balance = $("#addBalanceHidrico").attr("aria-pressed");
      console.log(balance);
      if(balance=="false"){
        $('#tiene_balance_hidrico').val(true);
      }else{
        $('#tiene_balance_hidrico').val(false);
      }
    });

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

    $('#funciones-vitales').on('click', function () {
      toggleFieldset('#funciones-vitales', '#fsfunciones-vitales');
      var funciones_vitales = $("#funciones-vitales").attr("aria-pressed");
      if(funciones_vitales=="false"){
        $('#tiene_funciones_vitales').val(true);
      }else{
        $('#tiene_funciones_vitales').val(false);
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

    $('#modal-hoja-recuperacion').modal({
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
    /****************GUARDAR BALANCE HIDRICO******************/
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

        if (!valid) {
            Swal.fire({
                title: "Información de Servicio de Registros",
                text: "Todos los campos de cantidad de ingresos y egresos deben ser 0 ó mayores",
                icon: "info",
                timer: 4000,
                showConfirmButton: true
            });
        }

        // Validación del campo `hora_registro`
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
            var route = '{{url('hoja_recuperacion_registrar_balance_hidrico')}}';
            
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
                                        listaBalanceHidrico(data.id_hoja_recuperacion);
                                        documento_pdf(data.id_hoja_recuperacion);
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

    function listaBalanceHidrico(id_hoja_recuperacion){
      $.ajax({
        url:'{{url('hoja_recuperacion_bh_listado/')}}/'+id_hoja_recuperacion,
        type:'GET',
        dataType:'JSON', 
          success: function(data){   
            console.log(data.results);
            $("#listaBalanceHidrico").empty();
            if (Array.isArray(data.results) && data.results.length > 0) {
              var valor = '' ;
              $.each(data.results, function (key, value) {
                  valor = valor+
                  '<tr >'+
                  ' <td style="vertical-align: middle;">'+(key+1)+'</td>'+
                  ' <td style="vertical-align: middle;">'+value.turno +'</td>'+
                  ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.diferencia +' </td>'+ 
                  ' <td style="vertical-align: middle;text-transform: uppercase;">'+value.bh24+'</td>';
                  valor +='</tr>';
              });
              $("#listaBalanceHidrico").html(valor);
              
            }else{
              
              swal({
                buttons: false,
                title: "Sin Historial",
                text: "No se han detectado registros durante la búsqueda realizada.",
                icon: "warning",
                timer: 1500,
              }); 

            }
          }
      });
    }
    
    $('#finalizar_hoja_recuperacion').on('click', function() {
      $('#modal-hoja-recuperacion').modal('hide');
      Swal.fire({
        title: "Registro Exitoso",
        text: "Datos registrados Exitosamente.",
        icon: "success",
        timer: 1500,
        showConfirmButton: false
      });
    });

    $('#FormularioFuncionVital').on('submit', function(e) {
        e.preventDefault(); 
        var token = $('#_token').val();
        var formData = new FormData(this);
        $.ajax({
            url: '{{url('hoja_recuperacion_registrar_funciones_vitales')}}',  
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false, 
            headers: {
                'X-CSRF-TOKEN': token 
            },
            success: function(response) {
              console.log(response);
              if (response.success) {

                  $('#modal-hoja-recuperacion').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
                  $('#modalFuncionVital').modal('hide'); 
                  setTimeout(function() {
                      $('body').addClass('modal-open');
                      $('#modal-hoja-recuperacion').css('overflow', 'auto');
                  }, 300);
                 
                  $('#FormularioFuncionVital').trigger("reset");
                  $('#modalFuncionVital').modal('hide');
                  Swal.fire({
                  title: "Registro Exitoso",
                  text: "Datos registrados Exitosamente.",
                  icon: "success",
                  timer: 1500,
                  showConfirmButton: false
                  });

                  if (response && Array.isArray(response.lista)) {
                    var encabezados = ''; 
                    var valores = ''; 

                    encabezados += '<tr>';
                    $.each(response.encabezado, function (key, value) {
                        encabezados += '<th style="vertical-align: middle;">' + value.descripcion_corta + '</th>';
                    });
                    encabezados += '</tr>';
                    $("#tbl_funciones_vitales thead").html(encabezados);
                    $.each(response.lista, function (key, fila) { 
                        valores += '<tr>';
                          $.each(fila.registros, function (campo, valor) {
                            if(valor.secuencia == 1){
                              //var contenido = valor !== null ? valor : '';
                              valores += '<td style="vertical-align: middle;">' + valor.hora_registro + '</td>';
                            }else{
                             // var contenido = valor !== null ? valor : '';
                              valores += '<td style="vertical-align: middle;">' +  valor.valor_caracter_fisico + '</td>';
                            }
                          });
                        
                        valores += '</tr>';
                    });
                    $("#tbl_funciones_vitales tbody").html(valores);
                    documento_pdf($('#id_hoja_recuperacion_vital').val());
                  }else{
                    swal({
                      buttons: false,
                      title: "Sin Historial",
                      text: "No se han detectado registros durante la búsqueda realizada.",
                      icon: "warning",
                      timer: 1500,
                    }); 

                  }
                  

              } else {
                validaCamposFV(response);
                Swal.fire({
                title: "Error de Servicio de Registros",
                text: "Verifique que todos los datos ingresados sean los correctos, en caso que el problema persista, comuníquese con el área de informática.",
                icon: "error",
                timer: 2500,
                buttons: false
                });
              }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Error al enviar el formulario');
            }
        });
    });
    $('.cerrar-modal-hijo').on('click', function() {
        $('#modalFuncionVital').modal('hide');
        $('body').addClass('modal-open');
        $('#modal-hoja-recuperacion').css('overflow', 'auto');
    });

    function validaCamposFV(response){
      $('input[name="caracter_fisico[]"]').each(function() {
          if ($(this).val() === '') {
              $(this).addClass('is-invalid'); 
          } else { 
            $(this).removeClass('is-invalid'); 
          }
      });
    }
  });
  function documento_pdf(id_hoja_recuperacion){
    let valor = '';
    valor += '<a href="/hoja_recuperacion_pdf/' + id_hoja_recuperacion + '" target="_blank" data-toggle="tooltip" title="Sin firma"';
    valor += ' class="btn enf_btn-verde-oscuro btn-xs">';
    valor += '<i class="fa fa-download"></i> Descargar PDF';
    valor += '</a>';
    $("#documento_pdf").html(valor);
  }

  function funcionVital() {
    var route = '{{url('hoja_recuperacion_caracter_fisico_vitales')}}';
    $.ajax({
      url: route,
      type:'GET',
      dataType:'JSON', 
        success: function(data){   
          $("#contenedor-formulario").empty();

            $.each(data.results, function (key, value) {
              var col = $('<div>', { class: 'col-md-4' }); 
              var label = $('<label>', { text: value.descripcion_corta }); 
                if(key === 0){
                  var input = $('<input>', {
                    type: 'time',                   
                    class: 'form-control',           
                    name: 'caracter_fisico[]',                    
                    value: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) 
                  });
                }else{
                  var input = $('<input>', {
                      type: 'text',              
                      class: 'form-control',    
                      name: 'caracter_fisico[]'
                  });
                }
                var inputHidden = $('<input>', {
                    type: 'hidden',                
                    name: 'caracter_fisico_id[]',    
                    value: value.id                 
                });

                col.append(label).append(input).append(inputHidden);

                $('#contenedor-formulario').append(col);
            });
            
        }
    }); 

    var scrollTop = $(window).scrollTop();
    $('#modalFuncionVital').css({
        'top': scrollTop + 'px',
        'position': 'absolute'
    });
    $('#modalFuncionVital').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');
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

  $('.select-diagnostico').each(function (index) {
    const valorSeleccionado = $(this).val(); // esto funciona aunque esté disabled
    console.log('Valor seleccionado:', valorSeleccionado);

    $('.id_diagnostico_hidden').eq(index).val(valorSeleccionado ?? '');
  });
</script>



  <div id="seccion1" class="seccion seccion-activa">
    
      <form name="form1" id="form1" role="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_intraoperatorio" value="{{ isset($intraoperatorio->id) ? $intraoperatorio->id : '' }}">
        <input type="hidden"id="id_asegurado"  name="id_asegurado" value="{{ $asegurado['id_asegurado'] }}">
        <input type="hidden" id="tiene_balance_hidrico" name="tiene_balance_hidrico" value="false">
        <input type="hidden" id="tiene_tratamiento" name="tiene_tratamiento" value="false">
        <input type="hidden" id="tiene_funciones_vitales" name="tiene_funciones_vitales" value="false">
        <input type="hidden" id="tiene_diagnostico_enfermeria" name="tiene_diagnostico_enfermeria" value="false">
        <input type="hidden" name="id_prestacion" value="{{$asegurado['id_prestacion']}}">
        <input type="hidden" name="id_nivel" id="id_nivel" value="<?php echo $nivel?>">
        <input type="hidden" id="tiene_analisis" name="tiene_analisis" value="false">
        <input type="hidden"id="id_intervencion"  name="id_intervencion" value="{{ $asegurado['id_intervencion'] }}">
        

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
                <input type="text" id="cama" name="cama"  class="form-control" value="{{!empty($cama) && $cama->cama?$cama->cama:''}}">
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
                <textarea  name="descripcion_tratamiento" id="descripcion_tratamiento" cols="50" rows="7" class="form-control"></textarea>
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
                <textarea  name="descripcion_analisis" id="descripcion_analisis" cols="50" rows="7" class="form-control"></textarea>
              </div>
              <div class="col-md-6">
              <table class="table " cellspacing="0" width="100%" cellspacing="0" width="100%">
                <tr>
                  <td>FECHA:</td>
                  <td><input type="date" class="form-control" id="fecha_analisis" name="fecha_analisis"></td>
                </tr>
                <tr>
                  <td>CVC:</td>
                  <td><input type="text" class="form-control" id="cvc_analisis" name="cvc_analisis"></td>
                </tr>
                <tr>
                  <td>SF:</td>
                  <td><input type="text" class="form-control" id="sf_analisis" name="sf_analisis"></td>
                </tr>
                <tr>
                  <td>L.A:</td>
                  <td><input type="text" class="form-control" id="la_analisis" name="la_analisis"></td>
                </tr>
                <tr>
                  <td>LPP:</td>
                  <td><input type="text" class="form-control" id="lpp_analisis" name="lpp_analisis"></td>
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
                  <input type="checkbox" id="campo1_diagnostico_enfermeria" name="campo1_diagnostico_enfermeria" checked value="1">
              </div>
              <div class="diagnostic-item1">
                  2.- LIMPIEZA INEFICAZ DE LAS VÍAS AÉREAS RELACIONADO CON LA RETENCIÓN DE LAS SECRECIONES SECUNDARIO A<br>
                  ESTADO DE INCONSCIENCIA
                   <input type="hidden" name="campo2_diagnostico_hidden" id="campo2_diagnostico_hidden" value="0">
                  <input type="checkbox" id="campo2_diagnostico_enfermeria" name="campo2_diagnostico_enfermeria" value="1">
              </div>
              <div class="diagnostic-item1">
                  3.- TERMORREGULACIÓN INEFICAZ RELACIONADO CON TEMPERATURA DEL AMBIENTE LUCTUANTE
                  <input type="hidden" name="campo3_diagnostico_hidden" id="campo3_diagnostico_hidden" value="0">
                  <input type="checkbox" id="campo3_diagnostico_enfermeria" name="campo3_diagnostico_enfermeria" checked value="1">
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
                    <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="addProcedimientoHRForm">Agregar Procedimiento</button>
                    <span class="invalid-feedback" style="display: none; color: red;">
                      {{ $errors->first('addProcedimientoHR') }}
                    </span>
                </div>
                <div class="col-lg-12">
                  <table class="display" id="tblProcedimientoHRForm" cellspacing="0" width="100%" style="margin-top:5px;">
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
      </form>
      <button type="button" id="guardarSiguiente">Guardar y Siguiente</button>
   
  </div>

  <!-- Sección 2 -->
  <div id="seccion2" class="seccion">
    <div class="panel panel-info contenedor-datos-generales">
      <div class="panel-body">
        <div class="row intro-contenedor">
          <div class="col-lg-4">
            <label>NOMBRE DEL PACIENTE :</label>
            <input type="text" readonly class="form-control" value="{{ $datos_paciente_intervencion['paciente']}}">
          </div>
          <div class="col-lg-2">
            <label for="tipo"><b>EDAD :</b></label>
            <input type="text" readonly class="form-control" value="{{ $datos_paciente_intervencion['fec_nacim_descrip']}}">
          </div>
          <div class="col-lg-3">
            <label class="control-label">CAMA :</label>
            <input type="text" id="cama2"  readonly class="form-control" value="{{!empty($cama) && $cama->cama?$cama->cama:''}}">
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

    <form id="form2">
      <form name="form2" id="form2" role="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_intraoperatorio" value="{{ isset($intraoperatorio->id) ? $intraoperatorio->id : '' }}">
        <input type="hidden" name="id_asegurado" value="{{ $asegurado['id_asegurado'] }}">
        <input type="hidden" id="tiene_balance_hidrico" name="tiene_balance_hidrico" value="false">
        <input type="hidden" id="tiene_tratamiento" name="tiene_tratamiento" value="false">
        <input type="hidden" id="tiene_funciones_vitales" name="tiene_funciones_vitales" value="false">
        <input type="hidden" id="tiene_diagnostico_enfermeria" name="tiene_diagnostico_enfermeria" value="false">
        
        <div class="panel panel-info contenedor-datos-generales">
          <div class="panel-body" style="width: 1100px;">
            <div class="row intro-contenedor">
              <div class="col-sm-7">
                <label>BALANCE HÍDRICO</label>
              </div>
              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                <button tabindex="70" type="button" id="addBalanceHidrico" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <div class="handle"></div>
                </button>
              </div>
            </div>
          </div>
          <fieldset id="fsaddBalanceHidrico" style="display:none;">
          
              <div class="col-md-12">
                  <div class="table-responsive">
                    <a href="javascript:void(0)" aria-hidden="true"
                                                      data-toggle="tooltip" title="Nuevo balance Hídrico"
                                                      class="btn btn-success btn-sm " id="crearBalanceHidrico">NUEVO BALANCE HÍDRICO 
                                                  </a>
                    <table class="table table-bordered" id="tblDiagnosticos" cellspacing="0" width="100%" style="margin-top:5px;">
                      <thead>
                        <tr>
                          <th width="2%">#</th>
                          {{-- <th width="5%" style="text-align: center;">PESO</th>--}}
                          <th width="3%" style="text-align: center;">TURNO</th>
                          <th width="10%" style="text-align: center;">BH POR TURNO</th>
                          <th width="10%" style="text-align: center;">BH 24 HORAS</th>
                          {{-- <th width="20%" style="text-align: center;">VER / EDITAR</th>--}}
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody id="listaBalanceHidrico">
                    
                      </tbody>
                    </table>
                  </div>
              </div>
            
          </fieldset>
        </div>
        <div class="panel panel-info contenedor-datos-generales">
          <div class="panel-body">
            <div class="form-group">
              <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                <label>FUNCIONES VITALES</label>
              </div>
              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                  <button tabindex="70" type="button" id="funciones-vitales" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <div class="handle"></div>
                  </button>
              </div>
            </div>
          </div>
          <fieldset id="fsfunciones-vitales" style="display:none;">
              <div class="row">
                  <div class="col-md-3">
                      <label>&nbsp;</label><br><br>
                      <a href="javascript:void(0)" aria-hidden="true" class="btn btn-success btn-sm" onClick="funcionVital()">NUEVA FUNCION VITAL</a>
                  </div>
              </div>
              <br>
              <div style="padding: 5px;">
                <table id="tbl_funciones_vitales"class="table table-bordered">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
              </div>
          </fieldset>
        </div>
        <fieldset>
          <div id="documento_pdf"></div>
        </fieldset>
       
      </form>
      <button type="submit" id="finalizar_hoja_recuperacion">Finalizar</button>
    </form>
  </div>


<!-- Modal FUNCIONES VITALES -->

<div class="modal fade" id="modalFuncionVital" tabindex="-1" role="dialog" aria-labelledby="modalFuncionVitalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 58%;" role="document">
    <div class="modal-content">
        <div class="modal-header" style="font-size: 20px !important;">
          <button type="button" class="close cerrar-modal-hijo" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            <b>NUEVA FUNCION VITAL</b>
        </div>
        <div style="font-size:11px;margin-left:30px;" class="panel-body">
          <form id="FormularioFuncionVital" name="FormularioFuncionVital" enctype="multipart/form-data"  method="POST">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="id_hoja_recuperacion_vital" name="id_hoja_recuperacion">
            <div class="row" id="contenedor-formulario">
             
            </div>
           

            <div class="btn-funcionvital">
              <button type="submit" class="btn btn-success guardar" id="GuardarFuncionVital" value="create">GUARDAR</button>
              <button type="button" class="btn btn-danger pull-left cerrar-modal-hijo">CANCELAR</button>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>
@include('frontend.centro_quirurgico.funciones_bh')

